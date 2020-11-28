<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;

class ContactsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        /*return new contact([
            //
        ]);*/
    }
}
