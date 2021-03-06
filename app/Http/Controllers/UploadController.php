<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadController extends GlobalController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        return view('pages/upload')->with("data", $this->getViewData());
    }

    public function uploadAsset(Request $request) {

        $validation     = $this->validate($request,
        [
          'name' => 'required|max:255|unique:skins,name',
          'assetType' => 'required',
          'author' => 'required',
          'file' => 'required|image|mimes:png|max:10240',
        ],[
          'name.unique' => 'This Asset already exists.'
        ]);

        if ($validation && $request->hasFile('file') && $request->file('file')->isValid()) {
            
            $name = $request->name;
            $assetType = $request->assetType;
            $author = $request->author;
            $file = $request->file;
            $fileExtension = $file->extension();
            $fileName = $name . '.' . $fileExtension;

            $this->handleStorageAndUpload($assetType, $fileName, $file, $name, $author);

            return "success";
        }
        else {
            return "failed";
        }
    }

    private function handleStorageAndUpload($assetType, $fileName, $file, $name, $author) {
        if(Storage::disk($assetType)->put($fileName, file_get_contents($file))) {
            DB::table($assetType)->insert(
                [
                    'name' => $name,
                    'author' => $author,
                    'imagePath' => '/database/'.$assetType.'/' . $fileName ,
                    'userID' => Auth::id(),
                    'isPublic' => 0,
                    'likes' => 0,
                    'downloads' => 0,
                    'uploadDate' => NOW()
                ]
            );
        }
    }

    private function getViewData() {
        $viewData = [
            'globalData' => $this->getGlobalPageData(),
        ];

        return json_encode($viewData);
    }
}
