<?php

use App\Http\Controllers\DownloadInvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{order}/pdf/download', DownloadInvoiceController::class)->name('order.pdf.download');
