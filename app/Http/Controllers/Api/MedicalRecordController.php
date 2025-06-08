<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index() {
        return MedicalRecord::with('patient')->get();
    }

    public function store(Request $request) {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'diagnosis' => 'required',
            'treatment' => 'required',
            'recorded_at' => 'required|date',
        ]);

        return MedicalRecord::create($request->all());
    }

    public function show(MedicalRecord $medicalRecord) {
        return $medicalRecord->load('patient');
    }

    public function update(Request $request, MedicalRecord $medicalRecord) {
        $medicalRecord->update($request->all());
        return $medicalRecord;
    }

    public function destroy(MedicalRecord $medicalRecord) {
        $medicalRecord->delete();
        return response()->noContent();
    }
}
