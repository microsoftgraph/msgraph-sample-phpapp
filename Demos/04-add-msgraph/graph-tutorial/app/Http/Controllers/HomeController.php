<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function welcome()
  {
    $viewData = [];

    // Check for flash errors
    if (session('error')) {
      $viewData['error'] = session('error');
      $viewData['errorDetail'] = session('errorDetail');
    }

    if (session('userName'))
    {
      $viewData['userName'] = session('userName');
      $viewData['userEmail'] = session('userEmail');
    }

    return view('welcome', $viewData);
  }
}