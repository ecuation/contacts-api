<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\CustomAttribute;

class SaveCSVData
{
    public $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function make()
    {
        foreach ($this->data as $item) {
            $contact = Contact::create($item['mapped']);
            $this->saveAttributes($item, $contact);
        }
    }

    public function saveAttributes($item, $contact)
    {
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
            return true;
        }

        return false;
    }
}
