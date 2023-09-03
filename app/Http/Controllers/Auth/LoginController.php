<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        $data = $request->all();
        $adminUser = $this->callLoginAdminUserApi($data['email'], $data['password']);
        User::where('email', '=', $data['email'])->update(['token' => $adminUser["items"]["token"]]);

        return view('home');
    }

    private function callLoginAdminUserApi($email, $password)
    {
        $url = env("GC_GAME_URL") . "/admin/login_admin_user";
        $data = [
            "email" => $email,
            "password" => $password,
        ];
        
        $jsonData = json_encode($data);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            die('cURLエラー: ' . curl_error($ch));
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);
        if ($decodedResponse === null) {
            die('JSONデコードエラー: ' . json_last_error_msg());
        }

        return $decodedResponse;
    }
}
