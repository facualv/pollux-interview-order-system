<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\GenerateInvoiceService;
use Illuminate\Http\Response;

final class DownloadInvoiceController extends Controller
{
    public function __invoke(Order $order, GenerateInvoiceService $service): Response
    {
        return  $service->execute($order);
    }
}
