<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatusController extends Controller
{
    public function index()
    {
        return view('status.index', ['statuses' => Status::all()]);
    }
    
    public function destroy(Status $status)
    {
        $status->delete();
        Cache::forget('statuses');
        
        return redirect(route('status.index'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:statuses|max:100',
        ], [
            'required' => 'Поле :attribute  обязательное!',
            'unique'   => 'Поле :attribute дожно быть уникальным!',
        ], [
            'name' => 'Название',
        ]);
        
        Status::create($request->all());
        
        return redirect(route('status.index'));
    }
}
