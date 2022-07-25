<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfficialbusinessController extends Controller
{
    //

    public function officialbusiness()
    {
        return view('forms.officialbusiness.officialbusiness',
        array(
            'header' => 'forms',
        ));
    }
}
