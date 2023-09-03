<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $genreList = $this->callGenreApi();

        $response = [
            "genres" => $genreList
        ];

        return view('home', compact('response'));
    }

    private function callGenreApi()
    {
        $url = env("GC_GAME_URL") . "/genre/list_genre";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
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
