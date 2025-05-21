<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectTo());
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    protected function redirectTo()
    {
        $user = Auth::user();
        if (!$user) {
            return '/dashboard';
        }

        $permissions = $user->all_permissions;

        if (empty($permissions)) {
            return '/dashboard';
        }

        $permissionToRoute = [
            'xem_thanh_vien' => '/trung-lao',
            'diem_danh' => '/trung-lao',
            'to_chuc_su_kien' => '/thanh-nien',
        ];

        $redirectUrl = '/dashboard';
        foreach ($permissions as $permission) {
            if (isset($permissionToRoute[$permission])) {
                $redirectUrl = $permissionToRoute[$permission];
                break;
            }
        }

        return $redirectUrl;
    }
}
