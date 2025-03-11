<?php

namespace App\Adapters;

use App\Interfaces\InvoiceGenerator;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class DomPdfAdapter implements InvoiceGenerator
{
    public function generateInvoice(Order $record): Response
    {

        $data = 'DOM PDF IMPLEMENTATION';

        $pdf = Pdf::loadView('invoice', [
            'orderNumber' => $record->number,
            'message' => $data
        ]);

        return $pdf->download();
    }
}
