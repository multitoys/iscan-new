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
        Cache::forget('paper');
        
        return redirect(route('paper.index'));
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:papers|max:100',
        ]);
        
        Paper::create($request->all());
        
        return redirect(route('paper.index'));
    }
}
