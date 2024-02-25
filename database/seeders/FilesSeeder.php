<?php

namespace Database\Seeders;

use App\Models\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FilesSeeder extends Seeder
{
    public function run()
    {

        $files = Storage::files('public/documents');

        foreach ($files as $documentFile) {
            $document = [
                'name' => basename($documentFile),
                'path' => $documentFile,
                'mime_type' => Storage::mimeType($documentFile),
                'size' => Storage::size($documentFile),
                'checked'=>'0',
            ];

            File::create($document);
        }
    }
}
