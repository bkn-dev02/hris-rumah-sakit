<?php

namespace Modules\Security\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class SecurityController extends Controller
{

    public function index()
    {
        return view('security::index');
    }
}
