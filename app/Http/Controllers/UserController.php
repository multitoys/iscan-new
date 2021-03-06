<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index', ['users' => User::orderByDesc('is_active')->get()]);
    }

    public function destroy(User $user)
    {
        $user->is_active = false;
        $user->save();
        Cache::forget('users');

        return redirect(route('user.index'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'login'    => 'required|unique:users|max:50',
            'password' => 'required|min:4',
            'role'     => 'required|numeric',
        ], [
            'required' => 'Поле :attribute  обязательное!',
            'unique'   => 'Поле :attribute дожно быть уникальным!',
            'min'      => 'Поле :attribute дожно быть минимум :min символа!',
        ], [
            'login'    => 'Логин',
            'password' => 'Пароль',
        ]);

        User::create([
            'login'      => $request->login,
            'password'   => bcrypt($request->password),
            'last_name'  => $request->last_name,
            'first_name' => $request->first_name,
            'role'       => $request->role,
        ]);
        Cache::forget('users');

        return redirect(route('user.index'));
    }
}
