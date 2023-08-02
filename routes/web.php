<?php

use App\Http\Controllers\{JobController, MeController, ShortenedrUrlController, SocialiteController};
use App\Http\Livewire\{Job\CreateComponent, Job\EditComponent, Job\OverviewComponent};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Route};

Route::get('/', OverviewComponent::class)->name('index');

Route::get('/{job}', [JobController::class, 'view'])
     ->name('job.view')
     ->whereUuid('job');

Route::middleware('guest')->prefix('socialite')->name('socialite.')->group(function () {
    Route::get('{provider}/redirect', [SocialiteController::class, 'redirect'])
        ->name('redirect');
    Route::get('{provider}/callback', [SocialiteController::class, 'callback'])
        ->name('callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/me', MeController::class)->name('me');

    Route::get('/new', CreateComponent::class)
        ->name('job.create');

    Route::get('/{job}/edit', EditComponent::class)
         ->name('job.edit')
         ->whereUuid('job');

    Route::get('/bye', function (Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('index'));
    })->name('bye');
});

require __DIR__ . '/officine.php';

Route::get('/{shortened}', ShortenedrUrlController::class)->name('shortened');
