<?php

use Illuminate\Support\Facades\Route;

// ★コントローラー読み込み★
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// \app\Providers\AuthServiceProviderで作成した
// 権限付与をルートに設定している
// middleware(can:第一引数)で入力をすればOK
Route::prefix('manager')
->middleware('can:manager-higher')
->group(function(){
    Route::resource('events', EventController::class);

});

Route::middleware('can:user-higher')
->group(function(){
    Route::get('index', function () {
        dd('user');
    });
});
