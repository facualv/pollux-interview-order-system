<?php

namespace App\Interfaces;

use App\Models\Order;
use Illuminate\Http\Response;

interface InvoiceGenerator
{
    public function generateInvoice(Order $record): Response;
}
