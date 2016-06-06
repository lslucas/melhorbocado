<?php namespace App\Http\Controllers\Admin;

use App\Posts;
use App\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class DashboardController extends Controller {

  public function index()
  {
      /*
    $user = User::take(5)->get();
    $post = Posts::where('status', 1)->take(5)->orderBy('id', 'DESC')->get();
    $postTotal = Posts::where('status', 1)->count();
    $userTotal = User::count();
       */

    $data = [
        'post' => 0,
        'post_total' => 1,
        'user_total' => 2,
        'user' => 3,
    ];

    return view('admin.dashboard.index')->with('data', $data);
  }

}
