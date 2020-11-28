<?php

namespace App\Services;


class CSVImportService
{
    public $delimiter;

    public $file_path;

    public $default_fields;

    public $is_valid_csv;

    public function __construct($file_path, $delimiter = null)
    {
        $this->is_valid_csv = true;
        $this->file_path = $file_path;
        $this->delimiter = $delimiter;
        $this->default_fields = [
            'team_id',
            'name',
            'phone',
            'email',
            'sticky_phone_number_id',
        ];
    }

    public function getDelimiter()
    {
        $delimiters = [',' => 0, ';' => 0, "|" => 0, "\t" => 0];

        $handle = fopen($this->file_path, "r");
        $firstLine = fgets($handle);
        fclose($handle);
        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }


    public function formatItems($handle) {
        $auto_delimiter = $this->getDelimiter();
        $columns = fgetcsv($handle, null, $auto_delimiter);
        $rows = [];

        while (($data = fgetcsv($handle, null, $auto_delimiter)) !== FALSE) {
            if(count($columns) === count($data)) {
                $rows[] = array_combine($columns, $data);
            }
        }

        fclose($handle);

        return $this->getDataGroupedByMappedOrNotMapped($rows);
    }


    public function getDataGroupedByMappedOrNotMapped($rows)
    {
        $insert = [];
        foreach ($rows as $index => $row) {
            $insert[$index] = ['mapped' => [], 'unmapped' => []];
            foreach ($row as $row_index => $value) {
                $this->checkIsValidRowAndValidateCSV($row_index);

                if(in_array($row_index, $this->default_fields)) {
                    $insert[$index]['mapped'] += [$row_index => $value];
                }else {
                    $insert[$index]['unmapped'] += [$row_index => $value];
                }
            }
        }
        return $insert;
    }

    public function checkIsValidRowAndValidateCSV($row_index)
    {
        if(!strlen($row_index)) $this->is_valid_csv = false;
        return $this->is_valid_csv;
    }
}
