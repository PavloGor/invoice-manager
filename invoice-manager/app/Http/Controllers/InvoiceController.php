<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'number' => 'required',
            'client' => 'required',
            'client_email' => 'required|email',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'status' => 'required',
        ]);
        Invoice::create($data);
        return redirect()->route('invoices.index')->with('success', 'Інвойс створено!');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'number' => 'required',
            'client' => 'required',
            'client_email' => 'required|email',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'status' => 'required',
        ]);
        $invoice->update($data);
        return redirect()->route('invoices.index')->with('success', 'Інвойс оновлено!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Інвойс видалено!');
    }

    public function pdf(Invoice $invoice)
    {
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('invoice_'.$invoice->id.'.pdf');
    }

    public function send(Invoice $invoice)
    {
        Mail::to($invoice->client_email)->send(new InvoiceMail($invoice));
        return back()->with('success', 'Інвойс надіслано!');
    }
}
