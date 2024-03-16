<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Tour;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('frontend.master');
    }
}
