<?php namespace App\Core\Controllers;

use Input, Config, Confide;
use App\Core\Models\Image;

class Images extends Base
{
    public function upload()
    {
        if (Input::hasFile('file')) {
            $method = 'get'.studly_case(Input::get('image_type')).'Data';
            if (!method_exists('App\Core\Models\Image', $method)) {
                throw new \InvalidArgumentException('Invalid image type');
            }
            $data = Image::$method();

            $file     = Input::file('file');
            $dest     = public_path(Config::get('varaa.upload_folder').$data['imageable_type']::IMAGEABLE_PATH);
            $filename = str_random(6) . '_' . $file->getClientOriginalName();
            $file->move($dest, $filename);

            // OK new record to database
            $data['path'] = $data['imageable_type']::IMAGEABLE_PATH.'/'.$filename;
            $image = new Image($data);
            $image->user()->associate(Confide::user());
            $image->save();
        }
    }
}
