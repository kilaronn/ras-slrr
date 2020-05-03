<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\SLDataImport;
use App\SensorData;

class ImportController extends Controller
{
  public function index() {
    return view('importSLRR');
  }
  public function showUploadFile(Request $request) {
    //Collect the Data from Form
    $file = $request->file('csv_file');
    $parameter = $request->input('parameter');
    $station = $request->input('station');

    //Begin extraction of data from uploaded CSV file
    $collection = (new SLDataImport)->toCollection($file);
    $extraction = $collection[0];

    //Begin insertion of extracted data to Database
    foreach ($extraction as $data) {
      //Check if data source is for Rain Rate
      if($parameter == 'rr'){
        $date = Date(strtotime($data[0].' '.$data[1])*1000);
        SensorData::updateOrCreate(
            [
            'c_time' => $date,
            'c_sensed_parameter' => $parameter,
            'c_sensor' => $station
            ],
            [
            'c_value' => $data[2]
            ]
        );
        
      }else{
        SensorData::updateOrCreate(
            [
            'c_time' => Date(strtotime($data[0]))*1000,
            'c_sensed_parameter' => $parameter,
            'c_sensor' => $station
            ],
            [
            'c_value' => $data[1]
            ]
        );
      }
    }
    
    $metaData = array($file->getClientOriginalName(),$file->getSize(),$parameter,$station);
    
    // //File Name
    // echo 'File Name: '.$file->getClientOriginalName();
    // //File Extension
    // echo 'File Extension: '.$file->getClientOriginalExtension();
    // //File Real Path
    // echo 'File Real Path: '.$file->getRealPath();
    // // File Size
    // echo 'File Size: '.$file->getSize();
    // //File Mime Type
    // echo 'File Mime Type: '.$file->getMimeType();

    //Move Uploaded File
    $destinationPath = 'uploads';
    $file->move($destinationPath,$file->getClientOriginalName());

    return view('importSLRR')
    ->with('metaData', $metaData);
  }
}