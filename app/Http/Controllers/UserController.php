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
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'login'    => 'required|unique:users|max:50',
            'password' => 'required|min:4',
            'role'     => 'required|numeric',
        ]);
        
        User::create([
            'login'      => $request->login,
            'password'   => bcrypt($request->password),
            'last_name'  => $request->last_name,
            'first_name' => $request->first_name,
            'role'       => $request->role,
        ]);
        
        return redirect(route('user.index'));
    }
}
