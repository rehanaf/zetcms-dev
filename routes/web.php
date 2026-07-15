<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

Route::get('/', [FrontendController::class, 'homepage'])->name('home');
Route::get('/blog', [FrontendController::class, 'blogIndex'])->name('blog.index');

Route::post('/forms/{slug}', [FrontendController::class, 'submitForm'])
    ->middleware('throttle:5,1')
    ->name('form.submit');

Route::post('/newsletter/subscribe', [FrontendController::class, 'subscribeNewsletter'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');

// Comment route for posts (now without /blog prefix)
Route::post('/{slug}/comment', [FrontendController::class, 'storeComment'])
    ->middleware('throttle:5,1')
    ->name('blog.comment');

// Wildcard route for pages and posts
Route::get('/{slug}', [FrontendController::class, 'slugShow'])->name('page.show');
