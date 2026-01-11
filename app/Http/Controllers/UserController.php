<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Player;
use App\Models\Score;
use App\Models\Word;
use App\Models\Time;
use App\Models\Train;
use App\Models\Score2;

class UserController extends Controller{

    //insert the names in database
public function insertData(Request $request){
       DB::table('players')->insert([
        'name' => $request->name,
    ]);

 return response()->json([
        'success' => true,
        'message' => 'Inserted successfully'
    ]);
}

//fetch the names from database
public function getNames(){
        return response()->json(
        Player::select('id', 'name')->get()
    );
    }

//insert the score in table with null or invalid prevention
   public function saveScore(Request $request){
//traking player id and characters he/she caught 
        $playerId = $request->player_id;
    $score = $request->score;

    $request->validate([
        'player_id' => 'required|exists:players,id',//prevents null player_id
        'score' => 'required|integer'//stop invalid scores/requests
    ]);

    //insert vals in table (total chars caught per player_id)
    DB::table('free_fall')->insert([
        'player_id' => $request->player_id,
        'score' => $request->score,
    ]);

    //returns a row if player already played before on the basis of player_id or null if the player is playing for the first time 
    $stats = DB::table('free_fall_stats')->where('player_id', $playerId)->first();

    //if player already registered, just modify prev data
    if ($stats) {
     $total = $stats->total + $score;

$gamesCount = DB::table('free_fall')
    ->where('player_id', $playerId)
    ->count();

$average = round($total / max($gamesCount, 1),2); // avoid division by zero

$highScore = max($stats->high_score, $score);

$typingPoints = round(($highScore * 0.4 + $average * 0.6) / 2,2);

DB::table('free_fall_stats')
    ->where('player_id', $playerId)
    ->update([
        'total' => $total,
        'average' => $average,
        'high_score' => $highScore,
        'typing_points' => $typingPoints,
    ]);
    } 
    //if new player, make new data starting from 0


    else {
        DB::table('free_fall_stats')->insert([
            'player_id' => $playerId,
            'total' => $score,
            'average' => $score,
            'high_score' => $score,
            'typing_points'=>$score/2,
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Score saved successfully',
        'data' => $request->all()
    ]);
}

public function getScore(){
     return response()->json(
        Score::join('players', 'players.id', '=', 'free_fall_stats.player_id')
        ->select(
            'players.name as player_name',
            'free_fall_stats.player_id',
            'free_fall_stats.total',
            'free_fall_stats.average',
            'free_fall_stats.high_score',
            'free_fall_stats.typing_points'
        )
        ->orderByDesc('free_fall_stats.typing_points') // leaderboard sorting (optional)
        ->get()
     );
}

public function saveWords(Request $request){
    $playerId = $request->player_id;
    $words = $request->total_words;

    $request->validate([
        'player_id' => 'required|exists:players,id',//prevents null player_id
        'total_words' => 'required|integer'//stop invalid scores/requests
    ]);

    DB::table('words_per_min')->insert([
        'player_id' => $request->player_id,
        'total_words' => $request->total_words,
    ]);

     //returns a row if player already played before on the basis of player_id or null if the player is playing for the first time 
    $stats = DB::table('words_stats')->where('player_id', $playerId)->first();

    //if player already registered, just modify prev data
    if ($stats) {
     $total = $stats->total_words + $words;

$gamesCount = DB::table('words_per_min')
    ->where('player_id', $playerId)
    ->count();

$average = round($total / max($gamesCount, 1),2); // avoid division by zero

$highScore = max($stats->high_score, $total);

$typingPoints = round(($highScore * 0.4 + $average * 0.6) / 2,2);

DB::table('words_stats')
    ->where('player_id', $playerId)
    ->update([
        'total' => $total,
        'average' => $average,
        'high_score' => $highScore,
        'typing_points'=>$typingPoints,
    ]);
    } 
    //if new player, make new data starting from 0
    else {
        $total = $words;
$typingPoints = $words;

        DB::table('words_stats')->insert([
            'player_id' => $playerId,
            'total' => $total,
            'average' => $total,
            'high_score' => $total,
            'typing_points'=>$typingPoints/2,
        ]);
    }
    return response()->json([
        'success' => true,
        'message' => 'Score saved successfully',
        'data' => $request->all()
    ]);
}



//will be execute when user plays fire typing game
public function saveTime(Request $request)
{
    $request->validate([
        'player_id' => 'required|exists:players,id',
        'total_elapsed_time' => 'required|integer'
    ]);

    $playerId = $request->player_id;
    $totalSeconds = round($request->total_elapsed_time / 1000, 2);

    DB::table('elapsed_time')->insert([
        'player_id' => $playerId,
        'total_elapsed_time' => $totalSeconds,
    ]);

    $stats = DB::table('elapsed_time_stats')
        ->where('player_id', $playerId)
        ->first();

    $average = DB::table('elapsed_time')
        ->where('player_id', $playerId)
        ->avg('total_elapsed_time');

    $leastTime = DB::table('elapsed_time')
        ->where('player_id', $playerId)
        ->max('total_elapsed_time');

        dd($leastTime);

    $typingPoints =round(100*(1-($average/22)),2);

    if ($stats) {
        DB::table('elapsed_time_stats')
            ->where('player_id', $playerId)
            ->update([
                'average_seconds' => $average,
                'least_seconds' => $leastTime,
                'typing_points' => $typingPoints,
            ]);
    } else {
        DB::table('elapsed_time_stats')->insert([
            'player_id' => $playerId,
            'average_seconds' => $average,
            'least_seconds' => $leastTime,
            'typing_points' => $typingPoints,
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Time stats updated successfully'
    ]);
}


public function getWords(){
      return response()->json(
        Word::join('players', 'players.id', '=', 'words_stats.player_id')
        ->select(
            'players.name',
            'words_stats.total',
            'words_stats.average',
            'words_stats.high_score',
            'words_stats.typing_points'
        )
        ->orderByDesc('words_stats.typing_points') // leaderboard sorting (optional)
        ->get()
     );
}

public function getTime(){
     return response()->json(
        Time::join('players', 'players.id', '=', 'elapsed_time_stats.player_id')
        ->select(
                        'elapsed_time_stats.player_id',
            'players.name as player_name',
            'elapsed_time_stats.average_seconds',
            'elapsed_time_stats.least_seconds',
            'elapsed_time_stats.typing_points'
        )
        ->orderByDesc('elapsed_time_stats.typing_points') // leaderboard sorting (optional)
        ->get()
     );
}

public function saveTrain(Request $request)
{
    $request->validate([
        'player_id' => 'required|exists:players,id',
        'times_per_level' => 'required|array|min:1'
    ]);

    $playerID = $request->player_id;
    $levelReached = $request->level_reached;

    // ✅ USE ARRAY (not JSON)
    $timesPerLevelArray = array_map('floatval', $request->times_per_level);

    // Calculate average correctly
    $averageTime = array_sum($timesPerLevelArray) / count($timesPerLevelArray);
    $averageTime = round($averageTime, 2);

    // Typing points formula
    $typingPoints = round((($averageTime * 0.4) + ($levelReached * 0.6)) / 2, 2);

    // (optional) store JSON if needed
    $timesPerLevelJson = json_encode($timesPerLevelArray);

    DB::table('train')->insert([
        'player_id' => $playerID,
        'level_reached' => $levelReached,
        'avg_time' => $averageTime,
        'typing_points' => $typingPoints,
        // 'times_per_level' => $timesPerLevelJson // only if column exists
    ]);

    return response()->json([
        'success' => true,
        'average_time' => $averageTime,
        'typing_points' => $typingPoints
    ]);
}


public function getTrain(){
    $leaderboard = DB::table('train')
    ->join('players', 'train.player_id', '=', 'players.id')
    ->select(
        'players.name',//select name
        DB::raw('MAX(level_reached) as max_level'),//select maximum level reached by particular player
        DB::raw('ROUND(AVG(avg_time), 2) as avg_time'),//avg time particular player have taken to complete a level
        DB::raw('ROUND(AVG(typing_points), 2) as typing_points')
    )
    ->groupBy('train.player_id', 'players.name')
    ->orderByDesc('typing_points')
    ->get();

    return $leaderboard;
}

public function saveScoreII(Request $request){
    //traking player id and characters he/she caught 
    $playerId = $request->player_id;
    $score = $request->score;

    $request->validate([
        'player_id' => 'required|exists:players,id',//prevents null player_id
        'score' => 'required|integer'//stop invalid scores/requests
    ]);

    //insert vals in table (total chars caught per player_id)
    DB::table('free_fall__i_i')->insert([
        'player_id' => $request->player_id,
        'score' => $request->score,
    ]);

    //returns a row if player already played before on the basis of player_id or null if the player is playing for the first time 
    $stats = DB::table('free_fall__i_i_stats')->where('player_id', $playerId)->first();

    //if player already registered, just modify prev data
    if ($stats) {
     $total = $stats->total + $score;

$gamesCount = DB::table('free_fall__i_i')
    ->where('player_id', $playerId)
    ->count();

$average = round($total / max($gamesCount, 1),2); // avoid division by zero

$highScore = max($stats->high_score, $score);

$typingPoints = round(($highScore * 0.4 + $average * 0.6) / 2,2);

DB::table('free_fall__i_i_stats')
    ->where('player_id', $playerId)
    ->update([
        'total' => $total,
        'average' => $average,
        'high_score' => $highScore,
        'typing_points' => $typingPoints,
    ]);
    } 
    //if new player, make new data starting from 0


    else {
        DB::table('free_fall__i_i_stats')->insert([
            'player_id' => $playerId,
            'total' => $score,
            'average' => $score,
            'high_score' => $score,
            'typing_points'=>$score/2,
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Score saved successfully',
        'data' => $request->all()
    ]);
}

public function getScoreII(){
     return response()->json(
        Score2::join('players', 'players.id', '=', 'free_fall__i_i_stats.player_id')
        ->select(
            'players.name as player_name',
            'free_fall__i_i_stats.total',
            'free_fall__i_i_stats.average',
            'free_fall__i_i_stats.high_score',
            'free_fall__i_i_stats.typing_points'
        )
        ->orderByDesc('free_fall__i_i_stats.typing_points') // leaderboard sorting (optional)
        ->get()
     );
}
}
