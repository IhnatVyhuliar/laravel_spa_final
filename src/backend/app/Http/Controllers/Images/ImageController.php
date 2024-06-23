<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(
        private array $avilable = ['jpg', 'png', 'gif']
    ){}

    public function getImage(string $image)
    {
        if (in_array($image, $this->avilable))
        {
            if (!in_array(str_split($image, '.'), $this->avilable))
            {
                abort(404);
            }
            if (!file('public/comments/photo/'.$image))
            {
                abort(404);
            }
            return response()->file('public/comments/photo/'.$image);
        }
        else{
            abort(404);
        }
    }
}
