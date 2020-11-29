<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\CustomAttribute;
use App\Services\SaveCSVData;
use Illuminate\Http\Request;
use App\Services\CSVDataFormatter;

class ContactAPIController extends Controller
{
    public function import(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();

        if (($handle = fopen($path, "r")) !== FALSE) {
            $service = new CSVDataFormatter($path);
            $data = $service->formatItems($handle);

            if($service->is_valid_csv) {
                try {
                    $db_save = new SaveCSVData($data);
                    $db_save->make();
                }catch (\Exception $exception) {
                    return response()->json(['success' => false], 500);
                }
                return response()->json(['success' => true]);
            }

            return response()->json([
                'success' => false,
            ], 422);
        }
    }
}
