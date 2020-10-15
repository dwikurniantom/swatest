<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Games;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function index(){
        return view('game');
    }
    public function Create(Request $request){
        $today = Carbon::now(new \DateTimeZone('Asia/Jakarta'));
        try {
            $CreateGameId = Games::insertGetId([
                //'id' => $uuid,
                'mdate' => $request['mdate'],
                'stadium' => $request['stadium'],
                'team1' => $request['team1'],
                'team2' => $request['team2'],
            ]);
            if ($CreateGameId) {
                return response()->json([
                    'message' => 'Pertandingan berhasil di tambahkan',
                ], 200);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Terjadi kesalahan server'.$ex,
            ], 500);
        }
    }
    public function Select(Request $request){
        $keyword = $request['searchkey'];
        $Games = Games::select()
            ->offset($request['start'])
            ->limit($request['length'])
            ->when($keyword, function ($query, $keyword) {
                return $query->where('stadium', 'like', '%'.$keyword.'%')->orWhere('team1', 'like', '%'.$keyword.'%')->orWhere('team2', 'like', '%'.$keyword.'%');
            })->get();
        $GameCounter = Games::when($keyword, function ($query, $keyword) {
            return $query->where('stadium', 'like', '%'.$keyword.'%')->orWhere('team1', 'like', '%'.$keyword.'%')->orWhere('team2', 'like', '%'.$keyword.'%');
        })->count();
        return response()->json([
            'draw' => $request['draw'],
            'total_exist' => Games::count(),
            'total_filtered' => $GameCounter,
            'data' => $Games,
        ], 200);
    }
}
