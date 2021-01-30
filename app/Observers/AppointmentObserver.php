<?php

namespace App\Observers;

use Carbon\Carbon;

trait AppointmentObserver{
    
    protected static function boot(){

        parent::boot();

        static::creating(function($appointment){
            $appointment->datetime_end_appointment = 
                Carbon::parse($appointment->datetime_appointment)
                ->addHours($appointment->duration_appointment);
        });
    }
}