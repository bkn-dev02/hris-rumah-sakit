<?php

namespace Modules\Shared\Http\Controllers;

use App\Http\Controllers\Controller;

class SharedController extends Controller
{

    public function blank()
    {

        return view('shared::pages.blank');
    }
}
