<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \Illuminate\Http\UploadedFile;

class ContactControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testImport()
    {
        $this->withoutExceptionHandling();
        $file = resource_path('tests/sample_headers.csv');
        $file = resource_path('tests/sample.csv');
        $name = 'sample.csv';
        $path = sys_get_temp_dir().'/'.$name;

        copy($file, $path);

        $response = $this->post(route('contact.import'), [
            'csv_file' => new UploadedFile($path, $name, filesize($path), null, true),
        ], []);

        $response->assertStatus(200);
    }
}
