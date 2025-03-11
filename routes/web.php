<?php

use App\Http\Controllers\DownloadPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{record}/pdf/download', DownloadPdfController::class)->name('order.pdf.download');
