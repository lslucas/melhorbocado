<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SupportController extends Controller {

  public function index()
  {
    return 'Em breve';
    return view('admin.dashboard.index')->with('data', $data);
  }

}
