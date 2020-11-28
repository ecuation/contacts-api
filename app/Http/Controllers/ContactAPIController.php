<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CSVImportService;

class ContactAPIController extends Controller
{
    public function import(Request $request)
    {
        $map_fields = [
            'team_id',
            'name',
            'phone',
            'email',
            'sticky_phone_number_id',
        ];

        $path = $request->file('csv_file')->getRealPath();

        if (($handle = fopen($path, "r")) !== FALSE) {
            $service = new CSVImportService($path);
            $data = $service->formatItems($handle);

            // dd($service->is_valid_csv);
            dd($data);
        }
    }
}
