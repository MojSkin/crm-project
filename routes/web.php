<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Models\Setting;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
//    return redirect()->to('/login');
    return view('welcome');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login')->name('login');
Route::get('/test', function (){

//    $apiKey = 'cPexbXJ9yeHZw0NJ8GPWfTux78Yh1fappNa+1SBEUpQ';
//    $receptor = '09378125986';
//    $template = 'authorize';
//    $message  = "type=1&m1=&receptor=".$receptor."&template=".$template."&param1=salam!";
//    $curl = curl_init();
//    curl_setopt_array($curl, [
//        CURLOPT_URL => 'http://api.ghasedaksms.com/v2/send/verify',
//        CURLOPT_RETURNTRANSFER => true,
//        CURLOPT_ENCODING => "",
//        CURLOPT_MAXREDIRS => 10,
//        CURLOPT_TIMEOUT => 30,
//        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//        CURLOPT_CUSTOMREQUEST => "POST",
//        CURLOPT_POSTFIELDS => $message,
//        CURLOPT_HTTPHEADER => [
//            "apikey: ".$apiKey,
//            "cache-control: no-cache",
//            "content-type: application/x-www-form-urlencoded",
//        ]
//    ]);
//    $response = curl_exec($curl);
//    $err = curl_error($curl);
//    curl_close($curl);
//
//    if ($err) {
//        return (object) ['Result' => 'Error', 'Code' => $err];
//    } else {
//        return json_decode($response);
//    }


//    $array = config('app');
//    dd(\Illuminate\Support\Facades\Crypt::encrypt(json_encode($array)));
});

Route::get('/{pathMatch}', function () {
    return view('welcome');
})->where('pathMatch', ".*");
