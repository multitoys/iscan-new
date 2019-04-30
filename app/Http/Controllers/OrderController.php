<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Outsource;
use App\Models\Status;
use App\User;
use Illuminate\Http\Request;
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
            'statuses'   => Status::all(),
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
        //
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
}
