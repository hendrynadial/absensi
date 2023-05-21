<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if($user == null)
        {
            $arr['status'] = 0;
            $arr['message'] = "Email Tidak Terdaftar";
            return $arr;
        }

        if($user->profile != "Admin")
        {
            $arr['status'] = 0;
            $arr['message'] = "Hanya Admin yang bisa login";
            return $arr;
        }
        
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $arr['status'] = 1;
            $arr['message'] = "Login Berhasil";
            return $arr;
        }else{
            $arr['status'] = 0;
            $arr['message'] = "Periksa Email / Password";
            return $arr;
        }
    }
}
