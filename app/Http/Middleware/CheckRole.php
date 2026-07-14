<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Hierarki role — semakin tinggi angkanya, semakin besar aksesnya.
     * Role dengan level lebih tinggi otomatis bisa mengakses semua yang
     * diizinkan untuk role di bawahnya.
     */
    protected const HIERARCHY = [
        'author'      => 1,
        'editor'      => 2,
        'admin'       => 3,
        'super-admin' => 4,
    ];

    /**
     * Periksa apakah user memiliki level role yang cukup.
     * Penggunaan di route: middleware('role:admin') atau middleware('role:admin,editor')
     *
     * Contoh hierarki:
     *   middleware('role:editor') → author ✗ | editor ✓ | admin ✓ | super-admin ✓
     *   middleware('role:admin')  → author ✗ | editor ✗ | admin ✓ | super-admin ✓
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !$user->role) {
            abort(403, 'Akses ditolak.');
        }

        $userLevel = self::HIERARCHY[$user->role->slug] ?? 0;

        // Hitung level minimum yang dibutuhkan dari daftar role yang diizinkan
        $requiredLevel = collect($roles)
            ->map(fn ($slug) => self::HIERARCHY[$slug] ?? 0)
            ->min();

        if ($userLevel < $requiredLevel) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
