<?php

namespace App\Http\Controllers;

use App\Models\Outsource;
use App\Models\Paper;
use Illuminate\Http\Request;

class OutsourceController extends Controller
{
    public function index()
    {
        return view('outsource.index', ['outsources' => Outsource::all()]);
    }
    
    public function destroy(Outsource $outsource)
    {
        $outsource->delete();
        Cache::forget('outsources');
        
        return redirect(route('outsource.index'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:outsources|max:100',
            'code' => 'required|unique:outsources|max:10',
        ], [
            'required' => 'Поле :attribute  обязательное!',
            'unique'   => 'Поле :attribute дожно быть уникальным!',
        ], [
            'name' => 'Название',
            'code' => 'Код',
        ]);
        
        Outsource::create($request->all());
        
        return redirect(route('outsource.index'));
    }
}
