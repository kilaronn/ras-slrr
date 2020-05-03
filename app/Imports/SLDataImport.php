<?php

namespace App\Imports;

use App\SensorData;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class SLDataImport implements ToModel
{

    use Importable;
    
    public function model(array $rows)
    {
        //
    }
}
