<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('subjects.index');
});

Route::get('/subjects', [SubjectController::class, 'index'])
    ->name('subjects.index');

Route::post('/subjects', [SubjectController::class, 'store'])
    ->name('subjects.store');

Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])
    ->name('subjects.destroy');

Route::get(
    '/subjects/{subject}/categories',
    [CategoryController::class, 'index']
)->name('subjects.categories.index');

Route::post(
    '/subjects/{subject}/categories',
    [CategoryController::class, 'store']
)->name('subjects.categories.store');

Route::delete(
    '/subjects/{subject}/categories/{category}',
    [CategoryController::class, 'destroy']
)->name('subjects.categories.destroy');

Route::get(
    '/subjects/{subject}/cards',
    [CardController::class, 'index']
)->name('subjects.cards.index');

Route::get(
    '/subjects/{subject}/cards/create',
    [CardController::class, 'create']
)->name('subjects.cards.create');

Route::post(
    '/subjects/{subject}/cards',
    [CardController::class, 'store']
)->name('subjects.cards.store');

Route::get(
    '/subjects/{subject}/cards/{card}/edit',
    [CardController::class, 'edit']
)->name('subjects.cards.edit');

Route::put(
    '/subjects/{subject}/cards/{card}',
    [CardController::class, 'update']
)->name('subjects.cards.update');

Route::delete(
    '/subjects/{subject}/cards/{card}',
    [CardController::class, 'destroy']
)->name('subjects.cards.destroy');

Route::get(
    '/subjects/{subject}/study',
    [CardController::class, 'study']
)->name('subjects.cards.study');

Route::patch(
    '/subjects/{subject}/cards/{card}/studied',
    [CardController::class, 'markAsStudied']
)->name('subjects.cards.studied');

Route::patch(
    '/subjects/{subject}/cards/{card}/mastery',
    [CardController::class, 'updateMastery']
)->name('subjects.cards.mastery');

Route::patch(
    '/subjects/{subject}/cards/{card}/bookmark',
    [CardController::class, 'toggleBookmark']
)->name('subjects.cards.bookmark');