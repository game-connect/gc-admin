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
        $user = \Auth::user();
        $genreList = $this->callGetApi("/genre/list_genre", null);
        $gameList = $this->callGetApi("/link_game/" . $user["admin_user_key"] . "/list_game", $user["token"]);

        $response = [
            "genres" => $genreList,
            "games" => $gameList
        ];

        return view('home', compact('response'));
    }

    public function createGame(Request $request)
    {
        $user = \Auth::user();
        $data = $request->all();

        $json = [
            "game_title" => $data["game_title"],
            "game_image" => $data["game_image_base64"],
            "genre_key" => $data["genre"],
            "setting" => [
                "game_score" => $this->checkBool($data["score"]),
                "game_combo_score" => $this->checkBool($data["combo"]),
                "game_rank" => $this->checkBool($data["rank"]),
                "game_play_time" => $this->checkBool($data["time"]),
                "game_score_image" => $this->checkBool($data["image"]),
            ]
        ];

        $createGame = $this->callPostApi("/link_game/" . $user["admin_user_key"] . "/create_game", $user["token"], $json);

        $genreList = $this->callGetApi("/genre/list_genre", null);
        $gameList = $this->callGetApi("/link_game/" . $user["admin_user_key"] . "/list_game", $user["token"]);

        $response = [
            "genres" => $genreList,
            "games" => $gameList
        ];

        return redirect('home')->with($response);
    }

    private function callGetApi($path, $token)
    {
        $url = env("GC_GAME_URL") . $path;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $token,
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

    private function callPostApi($path, $token, $json)
    {
        $url = env("GC_GAME_URL") . $path;
        $jsonData = json_encode($json);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $token,
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

    private function checkBool($value)
    {
        if ($value === "true") {
            return true;
        }

        return false;
    }
}
