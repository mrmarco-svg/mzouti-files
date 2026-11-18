<?php
use Illuminate\Support\Facades\Route;
Route::view('/','app')->name('home');
Route::get('/healthz',fn()=>response()->json(['ok'=>true,'app'=>'MZOUTI Files']));