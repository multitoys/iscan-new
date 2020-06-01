<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    public function index()
    {
        return view('service.index', ['services' => Service::all()]);
    }

    public function destroy(Service $service)
    {
        $service->delete();
        Cache::forget('services');

        return redirect(route('service.index'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:services|max:100',
        ], [
            'required' => 'Поле :attribute  обязательное!',
            'unique'   => 'Поле :attribute дожно быть уникальным!',
        ], [
            'name' => 'Название',
        ]);

        Service::create($request->all());
        Cache::forget('services');

        return redirect(route('service.index'));
    }
}
