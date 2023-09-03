<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $adminRegister = $this->callRegisterAdminUserApi($data['name'], $data['email'], $data['password']);
        $adminLogin = $this->callLoginAdminUserApi($data['email'], $data['password']);

        return User::create([
            'admin_user_key' => $adminRegister["items"]["admin_user_key"],
            'token' => $adminLogin["items"]["token"],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    private function callRegisterAdminUserApi($name, $email, $password)
    {
        $url = env("GC_GAME_URL") . "/admin/register_admin_user";
        $data = [
            "name" => $name,
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
