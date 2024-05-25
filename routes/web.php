<?php

use App\Http\Controllers\MembershipController;
use Illuminate\Support\Facades\Route;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('landing');
});

Route::get('/members', function () {
    return view('members');
});
Route::get('/view', function () {
    return view('view');
});

Route::get('/scanner', function () {
    return view('scanner');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/members/{member}', [MembershipController::class, 'show'])->name('members.show');
Route::get('/members', [MembershipController::class, 'index'])->name('members');
Route::get('/members/csv-download', [MembershipController::class, 'generateCSV']);
Route::get('/members/pdf-download', [MembershipController::class, 'generatePDF']);
Route::post('/reservations/import-csv', [MembershipController::class, 'importCsv'])->name('reservations.import.csv');

