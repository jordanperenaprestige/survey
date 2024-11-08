<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\ViewModels\UserViewModel;

class LocalAdminController extends AppBaseController
{
    /************************************
    * 	    DASHBOARD MANAGEMENT		*
    ************************************/    
    public function index()
    {
        return view('portal.local_admin');
    }

    public function error404()
    {
        return view('portal.404');
    }
}
