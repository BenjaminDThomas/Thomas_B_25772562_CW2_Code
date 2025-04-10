<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Auth;

// Homepage route
Route::get('/', function () {
    return view('dashboard'); // Initial Loading Page
})->name('dashboard');

// Unauthenticated users can view and participate in questionnaires
Route::get('/questionnaires', [QuestionnaireController::class, 'index'])->name('questionnaires.index');
Route::get('/questionnaires/{id}', [QuestionnaireController::class, 'show'])->name('questionnaires.show');
Route::post('/questionnaires/{id}/submit', [QuestionnaireController::class, 'submit'])->name('questionnaires.submit');
Route::post('/questionnaires/{questionnaireId}/store-anonymous-response', [QuestionnaireController::class, 'storeAnonymousResponse'])->name('questionnaires.storeAnonymousResponse');

// Authentication routes
Auth::routes();

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Routes requiring authentication
Route::middleware('auth')->group(function () {
    // User dashboard
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Manage questionnaires (only for logged-in users)
    Route::get('/manage', [QuestionnaireController::class, 'manage'])->name('manage');
    Route::get('/create', [QuestionnaireController::class, 'create'])->name('create');
    Route::post('/questionnaires', [QuestionnaireController::class, 'store'])->name('questionnaires.store');
    Route::get('/questionnaires/{id}/edit', [QuestionnaireController::class, 'edit'])->name('questionnaires.edit');
    Route::put('/questionnaires/{id}', [QuestionnaireController::class, 'update'])->name('questionnaires.update');
    Route::delete('/questionnaires/{id}', [QuestionnaireController::class, 'destroy'])->name('questionnaires.destroy');
    
    // Export and retrieve responses
    Route::get('/questionnaires/{id}/export-responses', [QuestionnaireController::class, 'exportResponses'])->name('questionnaires.exportResponses');
    Route::get('/questionnaires/{id}/retrieve-responses', [QuestionnaireController::class, 'retrieveResponses'])->name('questionnaires.retrieveResponses');
    Route::get('/questionnaires/{questionnaireId}/responses/export', [ResponseController::class, 'export'])->name('responses.export');
    Route::get('/export-answers', 'ResponseController@export');
});

// Handle responses (submission allowed without login)
Route::post('/questionnaires/{questionnaire}/responses', [ResponseController::class, 'store'])->name('responses.store');

