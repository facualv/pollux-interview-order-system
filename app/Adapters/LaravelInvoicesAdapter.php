<?php

namespace App\Adapters;

use App\Interfaces\InvoiceGenerator;
use App\Models\Order;
use Illuminate\Http\Response;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Invoice;

final class LaravelInvoicesAdapter implements InvoiceGenerator
{
    public function generateInvoice(Order $record): Response
    {
        $record->load('items.product');

        $customer = new Buyer([
            'name' => 'John Doe',
            'custom_fields' => [
                'orderNumber' => $record->number,
            ],]);

        $invoice = Invoice::make()->name('LARAVEL INVOICES IMPLEMENTATION')->buyer($customer);

        foreach ($record->items as $item) {
            $item = InvoiceItem::make($item->product->name)->pricePerUnit($item->unit_price)->quantity($item->quantity);
            $invoice->addItem($item);
        }

        return $invoice->download();
    }
}
