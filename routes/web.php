<?php

use App\Http\Controllers\ListExportController;
use App\Livewire\AddItemsPage;
use App\Livewire\ListHistoryPage;
use App\Livewire\ShoppingListPage;
use Illuminate\Support\Facades\Route;

// Public: shared list access (no auth required)
Route::get('/list/{share_token}', ShoppingListPage::class)->name('list.shared');
Route::get('/list/{share_token}/export.json', ListExportController::class)->name('list.export');

// Auth-protected pages
Route::middleware('auth')->group(function () {
    Route::get('/', ShoppingListPage::class)->name('home');
    Route::get('/add', AddItemsPage::class)->name('add');
    Route::get('/history', ListHistoryPage::class)->name('history');
});
