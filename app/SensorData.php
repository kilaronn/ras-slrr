<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $table = 't_sensor_data';
    
    protected $fillable = ['c_time','c_value','c_sensed_parameter','c_sensor'];

    public $timestamps = false;

}
