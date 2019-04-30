<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Outsource;
use App\Models\Paper;
use App\Models\Service;
use App\Models\Status;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use LetsAds;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        dd(LetsAds::send('hello', env('LETSADS_SENDER'), '380637174385'));
//        $sms = LetsAds::status(125573782);
        $orders = Order::with(['user', 'status', 'client', 'service', 'outsource', 'sms1', 'sms2'])
                        ->when($request->filled('user'), function ($query) use ($request) {
                            return $query->where('user_id', $request->user);
                        })
                        ->when($request->filled('status'), function ($query) use ($request) {
                            return $query->where('status_id', $request->status);
                        })
                        ->when($request->filled('client'), function ($query) use ($request) {
                            return $query->whereHas('client', function ($query) use ($request) {
                                $query->where('name', 'like', "%".$request->client."%")
                                    ->orWhere('email', 'like', "%".$request->client."%")
                                    ->orWhere('phone', 'like', '%'.$request->client."%");
                            });
                        })
                        ->orderByDesc('id')->paginate(50);

        return view('order.index', [
            'statuses' => Status::all(),
            'users'    => User::all(),
            'orders'   => $orders,
            'request'  => $request,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $order = new Order();
        $order->user_id = auth()->id();
        $order->save();

        return redirect(route('order.edit', ['order' => $order->id]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return view('order.edit', [
            'order'      => $order,
            'outsources' => Outsource::all(),
            'services'   => Service::all(),
            'statuses'   => Status::all(),
            'papers'     => Paper::all(),
            'users'      => User::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        if (is_array($request->file('files')) && count($request->file('files'))) {
            foreach ($request->file('files') as $file) {
                $file_name = $file->getClientOriginalName();
                Storage::putFileAs(Order::FILES_DIR.'/'.$order->id, $file, $file_name);
            }
            $order->is_files = true;
        }

        $order->user_id = $request->user_id;
        $order->status_id = $request->status_id;
        $order->outsource_id = $request->filled('outsource_id') ? $request->outsource_id : null;
        $order->service_id = $request->service_id;
        $order->paper_id = $request->paper_id;
        $order->is_color = $request->has('is_color');
        $order->is_non_color = $request->has('is_non_color');
        $order->quantity = $request->filled('quantity') ? $request->quantity : null;
        $order->pay_type = $request->pay_type;
        $order->price_design = $request->price_design;
        $order->comment = $request->comment;
        $order->date_end = \Carbon::parse($request->date_end);

        $order->save();

        return redirect(route('order.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function downloadFile($order, $file_name)
    {
        $path = Order::FILES_DIR.'/'.$order;
        $file = $this->__getFile($file_name, $path);

        if ($file) {
            $headers = [
                'Content-Disposition'  => 'attachment; filename='.urlencode($file_name),
                'Content-Type'         => 'application/force-download',
                'Content-Type'         => 'application/octet-stream',
                'Content-Type'         => 'application/download',
                'Content-Description'  => 'File Transfer',
            ];
            return Storage::download($file['path'], $file['name'], $headers);
        }

        return back()->withErrors(['error_message' => __('navigations.document_not_found')]);
    }

    public function deleteFile(Order $order, $file_name)
    {
        $path = Order::FILES_DIR.'/'.$order->id;
        $file = $path.'/'.$file_name;

        $status = false;
        if (Storage::exists($file)) {
            Storage::delete($file);
            $status = true;

            if (!count(Storage::files($path))) {
                $order->update(['is_files' => false]);
            }
        }

        return response()->json(['status' => $status]);
    }

    private function __getFile($file_name, $path_group)
    {
        $file = $path_group.'/'.$file_name;

        if (Storage::exists($file)) {
            return ['path' => $file, 'name' => $file_name];
        }

        return false;
    }
}
