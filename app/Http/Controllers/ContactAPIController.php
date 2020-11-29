<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportCSVRequest;
use App\Http\Resources\ContactsAPIResource;
use App\Models\Contact;
use App\Services\SaveCSVData;
use Illuminate\Http\Request;
use App\Services\CSVDataFormatter;

class ContactAPIController extends Controller
{
    public function index()
    {
        $contacts = Contact::with('customAttributes')->get();
        return response()->json([
            'contacts' => ContactsAPIResource::collection($contacts)
        ]);
    }
    public function import(ImportCSVRequest $request)
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
                    return response()->json([
                        'success' => false,
                        'error' => $exception->getTraceAsString()
                    ], 500);
                }
                return response()->json(['success' => true]);
            }

            return response()->json([
                'success' => false,
                'message' => 'CSV not valid format'
            ], 422);
        }
    }
}
