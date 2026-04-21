<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::middleware('face.verified')->group(function () {
        Route::get('/games', [GameController::class, 'catalog'])->name('games.catalog');
        Route::get('/games/{game}', [GameController::class, 'play'])->name('games.play');
    });

    Route::middleware('role.management')->group(function () {
        Route::resource('/manage/games', GameController::class)
            ->except(['show'])
            ->names('manage.games');

        Route::patch('/manage/games/{game}/publish', [GameController::class, 'togglePublish'])
            ->name('manage.games.publish');
    });
});

use App\Http\Controllers\FaceEnrollmentController;
use App\Http\Controllers\FaceVerificationController;

Route::middleware('auth')->group(function () {
    Route::get('/verify-face', [FaceVerificationController::class, 'show'])->name('face.verify.show');
    Route::post('/verify-face', [FaceVerificationController::class, 'verify'])->name('face.verify');

    Route::post('/profile/face-enrollment', [FaceEnrollmentController::class, 'store'])->name('profile.face.enrollment');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
