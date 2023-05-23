<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Log;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('idn', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check the user's role and redirect accordingly
            if ($user->role == 'admin') {
                return redirect()->route('admin.home');
            } elseif ($user->role == 'dosen') {
                return redirect()->route('dosen.home');
            } elseif ($user->role == 'mahasiswa') {
                return redirect()->route('mahasiswa.home');
            } else {
                Auth::logout();
                throw ValidationException::withMessages([
                    'idn' => 'Invalid user role.',
                ]);
            }
        }

        return redirect()->route('login')->withErrors([
            'idn' => 'Invalid credentials.',
        ]);
    }
}
