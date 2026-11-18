<?php
use Illuminate\Support\Facades\Route;
Route::view('/', 'app')->name('home');
Route::view('/admin', 'admin')->name('admin.direct');
Route::get('/healthz', fn () => response()->json(['ok' => true, 'app' => 'MZOUTI Files']));
Route::get('/share/{slug}', fn (string $slug) => view('app', ['sharedSlug' => $slug]))->name('share');
