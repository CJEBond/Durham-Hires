<?php
namespace App\Classes;

use App\Bookings;
use App\booked_items;
use App\Settings;
use App\Admin;
use App\Classes\Common;

class pdf
{
    public static function createInvoice($id, $save = true)
    {
        $booking = Bookings::findOrFail($id);
        $bookedItems = booked_items::select('description', 'number', 'dayPrice', 'weekPrice')
            ->where('booked_items.bookingID', '=', $id)
            ->where('booked_items.number', '!=', '0')
            ->join('catalog', 'booked_items.item', '=', 'catalog.id')
            ->get();

        $hiresID = Settings::where('name', 'hiresManager')->firstOrFail()->value;
        $hiresManager = Admin::findOrFail(intval($hiresID));
        $hiresEmail = Settings::where('name', 'hiresEmail')->firstOrFail()->value;

        Common::calcAllCosts($booking, $bookedItems);

        $name = 'invoice_' . $id . '.pdf';

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.invoice', ['booking' => $booking, 'items' => $bookedItems, 'manager' => $hiresManager, 'hiresEmail' => $hiresEmail]);
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(5, 3, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 8, array(0, 0, 0));

        if ($save) {
            $pdf->save(base_path() . '/storage/invoices/' . $name);
            Bookings::findOrFail($id)->update(['invoice' => $name]);
        } else {
            return $pdf->stream();
        }
    }
}