<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'))->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', App\Livewire\Dashboard::class)->name('dashboard');

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

    Route::prefix('disputes')->name('disputes.')->group(function () {
        Route::get('/', App\Livewire\Disputes\TablePage::class)->name('index');
        Route::get('/create', App\Livewire\Disputes\CreatePage::class)->name('create');
        Route::get('/{dispute}/edit', App\Livewire\Disputes\UpdatePage::class)->name('edit');
        Route::get('/{dispute}', App\Livewire\Disputes\ViewPage::class)->name('view');

        Route::get('/{dispute}/resolution', App\Livewire\Resolutions\ViewPage::class)->name('resolution.view');
        Route::get('/{dispute}/resolution/create', App\Livewire\Resolutions\CreatePage::class)->name('resolution.create');
        Route::get('/{dispute}/resolution/edit', App\Livewire\Resolutions\UpdatePage::class)->name('resolution.edit');
    });

    Route::prefix('hearings')->name('hearings.')->group(function () {
        Route::get('/', App\Livewire\Hearings\TablePage::class)->name('index');
        Route::get('/create', App\Livewire\Hearings\CreatePage::class)->name('create');
        Route::get('/{hearing}/edit', App\Livewire\Hearings\UpdatePage::class)->name('edit');
        Route::get('/{hearing}', App\Livewire\Hearings\ViewPage::class)->name('view');
    });

    Route::prefix('officers')->name('officers.')->group(function () {
        Route::get('/', App\Livewire\Officers\TablePage::class)->name('index');
        Route::get('/create', App\Livewire\Officers\CreatePage::class)->name('create');
        Route::get('/{officer}/edit', App\Livewire\Officers\UpdatePage::class)->name('edit');
    });

    Route::prefix('judges')->name('judges.')->group(function () {
        Route::get('/', App\Livewire\Judges\TablePage::class)->name('index');
        Route::get('/create', App\Livewire\Judges\CreatePage::class)->name('create');
        Route::get('/{judge}/edit', App\Livewire\Judges\UpdatePage::class)->name('edit');
    });

    Route::prefix('people')->name('people.')->group(function () {
        Route::get('/', App\Livewire\People\TablePage::class)->name('index');
        Route::get('/create', App\Livewire\People\CreatePage::class)->name('create');
        Route::get('/{person}/edit', App\Livewire\People\UpdatePage::class)->name('edit');

        Route::get('/{person}', App\Livewire\People\ViewPage::class)->name('view');
        Route::get('/{person}/records/create', App\Livewire\CriminalRecords\CreatePage::class)->name('records.create');
    });

    Route::prefix('records')->name('records.')->group(function () {
        Route::get('/{record}/edit', App\Livewire\CriminalRecords\UpdatePage::class)->name('edit');
    });

    Route::prefix('criminals')->name('criminals.')->group(function () {
        Route::get('/', App\Livewire\Criminals\TablePage::class)->name('index');
    });
});

require __DIR__ . '/settings.php';
