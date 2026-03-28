<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'))->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', \App\Livewire\Users\TablePage::class)->name('index');
        // Route::get('/create', \App\Livewire\Users\CreatePage::class)->name('create');
        // Route::get('/{user}/edit', \App\Livewire\Users\EditPage::class)->name('edit');
    });
});

require __DIR__ . '/settings.php';