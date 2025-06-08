<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index() {
        return Prescription::with('appointment')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'medication_details' => 'required',
        ]);

        return Prescription::create($request->all());
    }

    public function show(Prescription $prescription) {
        return $prescription->load('appointment');
    }

    public function update(Request $request, Prescription $prescription) {
        $prescription->update($request->all());
        return $prescription;
    }

    public function destroy(Prescription $prescription) {
        $prescription->delete();
        return response()->noContent();
    }
}
