<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SLDataImport;
use App\SensorData;
use Carbon\Carbon;

class ChartController extends Controller
{

  public function chart(){

    function getDateStartEnd($dt){
      $dtt = Carbon::createFromTimestampMs($dt)->get('minute') % 10;
      if($dtt > 4){$dtt = $dtt - 5;}
      $dts = Carbon::createFromTimestampMs($dt)->sub($dtt,'minute')->isoFormat('x');
      $dte = Carbon::createFromTimestampMs($dt)->sub($dtt,'minute')->sub('second',1)->addMinute(5)->isoFormat('x');
      return array($dts,$dte);
    }
    
    // $rpi_data = SensorData::select('c_time','c_value','c_sensed_parameter')
    //             // ->where([
    //             //           ['c_sensor', '=', 'at']
    //             //         ])
    //             ->limit(1)
    //             ->orderBy('c_time')
    //             ->get();
    
    $rain_rate_info = array();
    $sound_level_info = array();
    $rpi_info = array();
    
    $rrValue = 0;
    $rrPerHour = 0;

    $slValue = 0;
    $slPerHour = 0;

    // foreach($rpi_data as $rpi_datum){

    //   $date = $rpi_datum->c_time;
    
    //   if(!$rrPerHour && !$slPerHour){
    //     [$dateStart,$dateEnd] = getDateStartEnd($rpi_datum->c_time);
    //   }
    //   if($date >= $dateStart && $date <= $dateEnd){

    //     if($rpi_datum->c_sensed_parameter == "rr"){
    //       $rrValue = $rrValue + $rpi_datum->c_value;
    //       $rrPerHour++;
    //     }
    //     elseif($rpi_datum->c_sensed_parameter == "sl"){
    //       $slValue = $slValue + $rpi_datum->c_value;
    //       $slPerHour++;
    //     }

    //   }else {

    //     $rrValue = $rrValue/($rrPerHour?$rrPerHour:1);
    //     $slValue = $slValue/($slPerHour?$slPerHour:1);

    //     // $rain_rate_info[] = array($dateStart, $rrValue);
    //     // $sound_level_info[] = array($dateStart, $slValue);

    //     $rpi_info[] = array($dateStart, $slValue, $rrValue);

    //     $rrValue = 0;
    //     $slValue = 0;

    //     $rrPerHour = 0;
    //     $slPerHour = 0;
        
    //     [$dateStart,$dateEnd] = getDateStartEnd($rpi_datum->c_time);

    //     if($rpi_datum->c_sensed_parameter == "rr"){
    //       $rrValue = $rrValue + $rpi_datum->c_value;
    //       $rrPerHour++;
    //     }
    //     elseif($rpi_datum->c_sensed_parameter == "sl"){
    //       $slValue = $slValue + $rpi_datum->c_value;
    //       $slPerHour++;
    //     }

    //   }
    // }
    // return $rain_rate_info;

    return view('chartSLRR')
    // ->with('rain_rate_info', json_encode($rain_rate_info,JSON_NUMERIC_CHECK))
    // ->with('sound_level_info', json_encode($sound_level_info,JSON_NUMERIC_CHECK))
    ->with('rpi_info', $rpi_info);
  }

}