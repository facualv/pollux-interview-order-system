<?php

namespace App\Services;

use App\Interfaces\InvoiceGenerator;
use App\Models\Order;
use Illuminate\Http\Response;

final class GenerateInvoiceService
{
    public function __construct(private readonly InvoiceGenerator $downloader)
    {
    }

    public function execute(Order $record): Response
    {
        try {
            return $this->downloader->generateInvoice($record);
        } catch (\Exception $exception) {
            return response('Failed to generate invoice, please try again later', 500);
        }
    }

}
