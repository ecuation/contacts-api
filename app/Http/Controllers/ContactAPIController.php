<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\CustomAttribute;
use Illuminate\Http\Request;
use App\Services\CSVImportService;

class ContactAPIController extends Controller
{
    public function import(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();

        if (($handle = fopen($path, "r")) !== FALSE) {
            $service = new CSVImportService($path);
            $data = $service->formatItems($handle);

            if($service->is_valid_csv) {
                foreach ($data as $item) {
                    $contact = Contact::create($item['mapped']);

                    if(count($item['unmapped']))
                    {
                        $attr_data = $item['unmapped'];
                        foreach ($attr_data as $key => $value) {
                            $data = [
                                'key' => $key,
                                'value' => $value,
                                'contact_id' => $contact->id
                            ];

                            CustomAttribute::create($data);
                        }
                    }
                }

                return response()->json([
                    'success' => true,
                ]);
            }

            return response()->json([
                'success' => false,
            ], 422);
        }
    }
}
