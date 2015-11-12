<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DB;
use Request;

class FileController extends BaseController
{
    public function saveFile()
    {
        $file = Request::file('file');
	$name = $file->getClientOriginalName();
	$name = str_replace(' ', '_', $name);
        Storage::put('music/'.$name,  file_get_contents($file->getRealPath()));
        DB::table('files')->insert(
    ['name' => $name ]
);
        return response()->json('success');
    }


    public function deleteFile($name)
    {
        Storage::delete('music/'.$name);
        return response()->json('success');
    }

    public function getFileList(){

        $files = Storage::files('music');
	$response = [];
	foreach($files as $file){
	$file = str_replace('music/', '', $file);
	array_push($response,[
	'file' => $file
	]);
	}
        return response()->json($response);

    }

    public function viewFile($name){

        return response()->make(Storage::get('music/'.$name), 200, [
            'Content-Type' => Storage::mimeType('music/'.$name),
            'Content-Disposition' => 'inline; '.'music/'.$name,
        ]);

    }

}
