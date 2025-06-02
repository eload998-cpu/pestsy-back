<?php

namespace App\Http\Controllers\API\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManagementPlanController extends Controller
{

    public function __construct()
    {

        updateConnectionSchema("modules");

    }

    // Download All
    public function download_all($id)
    {

        $zip_file = 'planes.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $path = storage_path('app/public/files/ManagementPLan/' . $id);
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $name => $file) {
            // We're skipping all subfolders
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();

                // extracting filename with substr/strlen
                $relativePath = substr($filePath, strlen($path) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        $file_name = $zip_file;
        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename=' . $file_name . '',
            'Content-Type' => 'application/zip, application/octet-stream',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ];

        return response()->download($zip_file, $file_name, $headers);

    }

}
