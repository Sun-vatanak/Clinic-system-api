<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index() {
        return Invoice::with('user')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'invoice_date' => 'required|date',
        ]);

        return Invoice::create($request->all());
    }

    public function show(Invoice $invoice) {
        return $invoice->load('user');
    }

    public function update(Request $request, Invoice $invoice) {
        $invoice->update($request->all());
        return $invoice;
    }

    public function destroy(Invoice $invoice) {
        $invoice->delete();
        return response()->noContent();
    }
}
