<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;



class MediaHelper
{

    public static function StoreMedia($folderName,Request $request,$key ='media')
    {
            if ($request->hasFile($key)) {
                $image = $request->file($key);
                $imageName = Str::random(20) . '.' . $image->getClientOriginalExtension();
                $directory = public_path('storage/'.$folderName);
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $image->move($directory, $imageName);
                $path = asset('storage/'.$folderName.'/'.$imageName);
                return $path;
               }
               return null;
    }




}
