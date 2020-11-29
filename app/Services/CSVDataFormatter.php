<?php

namespace App\Services;


class CSVDataFormatter
{
    public $delimiter;

    public $file_path;

    public $map_fields;

    public $is_valid_csv;

    public function __construct($file_path, $delimiter = null)
    {
        $this->is_valid_csv = true;
        $this->file_path = $file_path;
        $this->delimiter = $delimiter;
        $this->map_fields = [
            'team_id' => ['required', 'integer'],
            'name' => [],
            'phone' => ['required'],
            'email' => [],
            'sticky_phone_number_id' => [],
        ];
    }

    public function getDelimiter()
    {
        $delimiters = [',' => 0, ';' => 0, "|" => 0, "\t" => 0];

        $file = fopen($this->file_path, "r");
        $firstLine = fgets($file);
        fclose($file);
        foreach ($delimiters as $delimiter => &$count) {
            $count = count(str_getcsv($firstLine, $delimiter));
        }

        return array_search(max($delimiters), $delimiters);
    }


    public function formatItems($file) {
        $auto_delimiter = $this->getDelimiter();
        $columns = fgetcsv($file, null, $auto_delimiter);
        $rows = [];

        while (($data = fgetcsv($file, null, $auto_delimiter)) !== FALSE) {
            if(count($columns) === count($data)) {
                $rows[] = array_combine($columns, $data);
            }
        }

        fclose($file);

        return $this->getDataGroupedByMappedOrNotMapped($rows);
    }

    public function getDataGroupedByMappedOrNotMapped($rows)
    {
        $insert = [];
        foreach ($rows as $index => $row) {
            $insert[$index] = ['mapped' => [], 'unmapped' => []];
            foreach ($row as $column_name => $value) {
                if(in_array($column_name, array_keys($this->map_fields))) {
                    $insert[$index]['mapped'] += [$column_name => $value];
                }else {
                    $insert[$index]['unmapped'] += [$column_name => $value];
                }
                $this->checkIsValidCSV($column_name, $value, $insert);
            }
        }

        return $insert;
    }

    public function checkIsValidCSV($column_name, $value, $insert)
    {
        if (! count($insert[0]['mapped'])) $this->is_valid_csv = false;

        if(! strlen($column_name) || ! isset($this->map_fields[$column_name]))
            return $this->is_valid_csv;

        if(in_array('required', $this->map_fields[$column_name]) && ! strlen($value))
            $this->is_valid_csv = false;

        if(in_array('integer', $this->map_fields[$column_name]) && ! is_numeric($value))
            $this->is_valid_csv = false;

        return $this->is_valid_csv;
    }

}
