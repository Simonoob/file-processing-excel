<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FileController extends Controller
{
    public function processExcelFile(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);


        $file_path = $request->file('file')->getRealPath();



        $import = Excel::toArray('', $file_path, null, \Maatwebsite\Excel\Excel::XLSX)[0];

        //grab all the data from the excel file and store it in a text file
        $file = fopen("processedExcelFile.txt", "w");
        foreach ($import as $line) {
            foreach ($line as $cell) {
                if ($cell != null) {
                    fwrite($file, $cell . ",");
                }
            }

            fwrite($file, "\n");
        }
        fclose($file);

        $fileContents = file_get_contents("processedExcelFile.txt");

        dd($fileContents);

        return back()->with('success', 'File processed successfully!')->with('fileContents', $fileContents);
    }
}
