<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallController1 extends Controller
{
    public function index (Request $request) {
        return 'CallController1@index';
    }
    public function create () {
        return 'CallController1@create';
    }
    public function read () {
        return 'CallController1@read';
    }
    public function update () {
        return 'CallController1@update';
    }
    public function delete () {
        return 'CallController1@delete';
    }
}
