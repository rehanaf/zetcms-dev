<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\HeadlessFrontendController;

Route::get('/', [HeadlessFrontendController::class, 'homepage'])->name('api.home');
Route::get('/blog', [HeadlessFrontendController::class, 'blogIndex'])->name('api.blog.index');

Route::post('/forms/{slug}', [HeadlessFrontendController::class, 'submitForm'])->middleware('throttle:5,1')->name('api.form.submit');

Route::post('/newsletter/subscribe', [HeadlessFrontendController::class, 'subscribeNewsletter'])->middleware('throttle:5,1')->name('api.newsletter.subscribe');

// Comment route for posts
Route::post('/{slug}/comment', [HeadlessFrontendController::class, 'storeComment'])->middleware('throttle:5,1')->name('api.blog.comment');

// Wildcard route for pages and posts
Route::get('/{slug}', [HeadlessFrontendController::class, 'slugShow'])->name('api.blog.show');
Route::get('/{slug}', [HeadlessFrontendController::class, 'slugShow'])->name('api.page.show');
