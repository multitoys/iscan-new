<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PaperController extends Controller
{
    public function index()
    {
        return view('paper.index', ['papers' => Paper::all()]);
    }

    public function destroy(Paper $paper)
    {
        $paper->delete();
        Cache::forget('papers');

        return redirect(route('paper.index'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:papers|max:100',
        ], [
            'required' => 'Поле :attribute  обязательное!',
            'unique'   => 'Поле :attribute дожно быть уникальным!',
        ], [
            'name' => 'Название',
        ]);

        Paper::create($request->all());
        Cache::forget('papers');

        return redirect(route('paper.index'));
    }
}
