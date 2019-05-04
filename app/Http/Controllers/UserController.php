<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index', ['users' => User::all()]);
    }
    
    public function destroy(User $user)
    {
        $user->delete();
        Cache::forget('users');
        
        return redirect(route('user.index'));
    }
}
