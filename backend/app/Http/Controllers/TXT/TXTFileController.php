<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TXTFileController extends Controller
{
    public function __construct(
        private array $avilable = ['txt']
    ){}

    public function getTXTFile(string $txt)
    {
        if (in_array($txt, $this->avilable))
        {
            if (!in_array(str_split($txt, '.'), $this->avilable))
            {
                abort(404);
            }
            if (!file('public/comments/txt'.$txt))
            {
                abort(404);
            }
            
            return response()->file('public/comments/txt'.$txt);
        }
        else{
            abort(404);
        }
    }
}
