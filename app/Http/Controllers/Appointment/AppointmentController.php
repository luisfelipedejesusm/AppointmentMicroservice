<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppointmentController extends Controller
{

    use ApiResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function index(){
        $appointments = Appointment::all();
        return $this->successResponse($appointments);
    }

    public function store(Request $request){
        $rules = [
            'customer_name'         => 'required|max:255',
            'customer_email'        => 'email',
            'datetime_appointment'  => 'required|date',
            'duration_appointment'  => 'required|numeric|max:3'
        ];
        
        $this->validate($request, $rules);
        
        // Check if any appointment overlaps the current appointment
        
        $appointmentDateStart = $request->datetime_appointment;
        
        $overlapingAppointments = Appointment::where('datetime_appointment', '<=', $appointmentDateStart)
            ->where('datetime_end_appointment', '>=', $appointmentDateStart)
            ->count();
        
        if($overlapingAppointments){
            return $this->errorResponse("An existing appointment exist in the selected time range", Response::HTTP_PRECONDITION_FAILED);
        }

        $appointment = Appointment::create($request->all());
        
        return $this->successResponse($appointment, Response::HTTP_CREATED);
    }

    public function show($appointment){
        $appointment = Appointment::findOrFail($appointment);
        return $this->successResponse($appointment);
    }

    public function update(Request $request, $appointment){
        $rules = [
            'customer_name'         => 'max:255',
            'customer_email'        => 'email',
            'datetime_appointment'  => 'date',
            'duration_appointment'  => 'numeric|max:3'
        ];

        $this->validate($request, $rules);

        $appointment = Appointment::findOrFail($appointment);

        $appointment->fill($request->all());

        if($appointment->isClean()){
            return $this->errorResponse("No changes to be made", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $appointment->save();

        return $this->successResponse($appointment);
    }

    public function destroy($appointment){
        
        $appointment = Appointment::findOrFail($appointment);

        $appointment->delete();

        return $this->successResponse($appointment);
    }

}
