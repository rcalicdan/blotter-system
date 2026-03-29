<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'))->name('home');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', App\Livewire\Users\TablePage::class)->name('index');
        Route::get('/create', App\Livewire\Users\CreatePage::class)->name('create');
        Route::get('/{user}/edit', App\Livewire\Users\UpdatePage::class)->name('edit');
    });

    Route::prefix('people')->name('people.')->group(function () {
        Route::get('/', App\Livewire\People\TablePage::class)->name('index');
        Route::get('/create', App\Livewire\People\CreatePage::class)->name('create');
        Route::get('/{person}/edit', App\Livewire\People\UpdatePage::class)->name('edit');
    });

    Route::prefix('blotter')->name('blotters.')->group(function () {
        Route::get('/', App\Livewire\Blotters\TablePage::class)->name('index');
        Route::get('/create', App\Livewire\Blotters\CreatePage::class)->name('create');
        Route::get('/{blotterEntry}/edit', App\Livewire\Blotters\UpdatePage::class)->name('edit');
        Route::get('/{blotterEntry}', App\Livewire\Blotters\ViewPage::class)->name('view');
    });
});

require __DIR__ . '/settings.php';
