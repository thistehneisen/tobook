<?php namespace App\Core\Controllers;

use Input, Config, Crypt;
use App\Core\Models\Image;

class Images extends Base
{
    public function upload()
    {
        if (Input::hasFile('file')) {
            $decoded = Crypt::decrypt(Input::get('data'));
            $imageableType = $decoded['imageable_type'];
            $imageableId   = $decoded['imageable_id'];

            $file     = Input::file('file');
            $dest     = public_path(Config::get('varaa.upload_folder').$imageableType::IMAGEABLE_PATH);
            $filename = str_random(6) . '_' . $file->getClientOriginalName();
            $file->move($dest, $filename);

            // OK new record to database
            $image = new Image([
                'path'           => $imageableType::IMAGEABLE_PATH.'/'.$filename,
                'imageable_id'   => $imageableId,
                'imageable_type' => $imageableType,
            ]);

            $image->save();
        }
    }
}
