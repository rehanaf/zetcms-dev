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

// Serve Theme Assets
Route::get('/theme-assets/{theme}/{path}', function ($theme, $path) {
    // Only allow alphanumeric, dash, and underscore for theme name to prevent path traversal
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $theme)) abort(404);
    
    // Path traversal protection for $path is handled by realpath check
    $baseDir = resource_path("views/themes/{$theme}");
    $fullPath = realpath($baseDir . '/' . $path);
    
    // Ensure the resolved path starts with the base directory
    if (!$fullPath || !str_starts_with($fullPath, $baseDir)) {
        abort(404);
    }
    
    // Determine mime type
    $mimeType = \Illuminate\Support\Facades\File::mimeType($fullPath);
    
    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000'
    ]);
})->where('path', '.*')->name('theme.asset');

// Wildcard route for pages and posts
Route::get('/category/{slug}', [FrontendController::class, 'categoryShow'])->name('category.show');
Route::get('/tag/{slug}', [FrontendController::class, 'tagShow'])->name('tag.show');
Route::get('/{slug}', [FrontendController::class, 'slugShow'])->name('page.show');
