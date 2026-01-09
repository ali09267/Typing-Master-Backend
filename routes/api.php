 <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('/insert', [UserController::class, 'insertData']);
Route::get('/get_names', [UserController::class, 'getNames']);
Route::post('/save-score',[UserController::class,'saveScore']);
Route::get('/get_score',[UserController::class,'getScore']);
Route::post('/save-words',[UserController::class,'saveWords']);
Route::get('/get_words',[UserController::class,'getWords']);
Route::post('/save-time',[UserController::class,'saveTime']);
Route::get('/get_time',[UserController::class,'getTime']);
Route::post('/save-train',[UserController::class,'saveTrain']);
Route::get('/get_train',[UserController::class,'getTrain']);
Route::post('/save-score-ii',[UserController::class,'saveScoreII']);
Route::get('/get_score_ii',[UserController::class,'getScoreII']);