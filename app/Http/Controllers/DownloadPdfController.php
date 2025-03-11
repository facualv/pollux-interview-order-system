<?php

namespace App\Http\Controllers;

use App\Models\Order;

class DownloadPdfController extends Controller
{
    public function __invoke(Order $record)
    {
        dd($record->load('items'));
    }
}
