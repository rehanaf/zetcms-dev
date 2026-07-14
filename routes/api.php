<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\HeadlessFrontendController;

Route::get('/', [HeadlessFrontendController::class, 'homepage'])->name('api.home');
Route::get('/blog', [HeadlessFrontendController::class, 'blogIndex'])->name('api.blog.index');
Route::get('/blog/{slug}', [HeadlessFrontendController::class, 'blogShow'])->name('api.blog.show');
Route::post('/blog/{slug}/comment', [HeadlessFrontendController::class, 'storeComment'])->middleware('throttle:5,1')->name('api.blog.comment');

Route::post('/forms/{slug}', [HeadlessFrontendController::class, 'submitForm'])->middleware('throttle:5,1')->name('api.form.submit');

Route::post('/newsletter/subscribe', [HeadlessFrontendController::class, 'subscribeNewsletter'])->middleware('throttle:5,1')->name('api.newsletter.subscribe');

Route::get('/{slug}', [HeadlessFrontendController::class, 'pageShow'])->name('api.page.show');
