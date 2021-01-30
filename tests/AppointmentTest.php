<?php

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Response;

class AppointmentTest extends TestCase
{

    protected $appointmentStructure = [
        'customer_name',
        'datetime_appointment',
        'duration_appointment'
    ];

    public function test_index_returns_correct_data(){
        $this->get("/appointments");
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([
            'data' => [ '*' => 
                $this->appointmentStructure
            ]
        ]);
    }

    public function test_save_new_appointment(){
        $appointment = Appointment::factory()->make()->toArray();
        $response = $this->post("/appointments", $appointment);
        $this->seeStatusCode(Response::HTTP_CREATED);
        $this->seeJsonStructure([
            'data' => $this->appointmentStructure
        ]);
    }

    public function test_show_one_specific_appointment(){
        $this->get("/appointments/1");
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([
            'data' => $this->appointmentStructure
        ]);
    }

    public function test_update_appointment(){
        $appointment = Appointment::factory()->make()->toArray();
        $this->put("/appointments/1", $appointment);
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([
            'data' => $this->appointmentStructure
        ]);
        
        $appointment = Appointment::factory()->make()->toArray();
        $this->patch("/appointments/1", $appointment);
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJsonStructure([
            'data' => $this->appointmentStructure
        ]);
    }

    public function test_delete_appointment(){
        $appointment = Appointment::where('id', '<>', '1')->first();
        $this->delete("/appointments/{$appointment->id}");
        $this->seeStatusCode(Response::HTTP_OK);
    }

    public function test_overlaping_appointments_exception(){
        $appointment = Appointment::factory()->create();
        $overlaping = Appointment::factory()->make()->toArray();
        $overlaping['datetime_appointment'] = $appointment->datetime_appointment;
        $this->post("/appointments", $overlaping);
        $this->seeStatusCode(Response::HTTP_PRECONDITION_FAILED);

        $overlaping['datetime_appointment'] = 
            Carbon::parse($appointment->datetime_appointment)
                ->addHours(4)
                ->format("Y-m-d H:i:s");
        $this->post("/appointments", $overlaping);
        $this->seeStatusCode(Response::HTTP_CREATED);
    }
}
