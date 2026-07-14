<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;

Route::get('/', [FrontendController::class, 'homepage'])->name('home');
Route::get('/blog', [FrontendController::class, 'blogIndex'])->name('blog.index');
Route::get('/blog/{slug}', [FrontendController::class, 'blogShow'])->name('blog.show');
Route::post('/blog/{slug}/comment', [FrontendController::class, 'storeComment'])
    ->middleware('throttle:5,1')
    ->name('blog.comment');

Route::post('/forms/{slug}', [FrontendController::class, 'submitForm'])
    ->middleware('throttle:5,1')
    ->name('form.submit');

// Wildcard page route: must be defined at the very end to avoid capturing other static routes
Route::post('/newsletter/subscribe', [FrontendController::class, 'subscribeNewsletter'])
    ->middleware('throttle:5,1')
    ->name('newsletter.subscribe');
Route::get('/{slug}', [FrontendController::class, 'pageShow'])->name('page.show');
