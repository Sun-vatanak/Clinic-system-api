<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index() {
        return Appointment::with(['doctor', 'patient'])->get();
    }

    public function store(Request $request) {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
        ]);

        return Appointment::create($request->all());
    }

    public function show(Appointment $appointment) {
        return $appointment->load(['doctor', 'patient']);
    }

    public function update(Request $request, Appointment $appointment) {
        $appointment->update($request->all());
        return $appointment;
    }

    public function destroy(Appointment $appointment) {
        $appointment->delete();
        return response()->noContent();
    }
}
