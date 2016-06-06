<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Admin area
Route::get('admin', function () { return redirect('/admin/dashboard'); });
Route::group([
  'namespace' => 'Admin',
  'middleware' => 'auth',
], function () {
  Route::get('admin/dashboard', 'DashboardController@index');

  Route::resource('admin/user', 'UserController');
  Route::resource('admin/products', 'ProductsController');
  Route::resource('admin/biz', 'BizController');

  Route::get('admin/cart', 'CartController@index');
  Route::put('admin/cart', 'CartController@concluir');
  Route::delete('admin/cart/unset/{id}', 'CartController@unsetItem');
  Route::get('admin/history', 'CartController@show');

  Route::resource('admin/support', 'SupportController');

  Route::post('admin/fileupload', function () {
      $upload = new \lslucas\Files\Upload();
      $upload->start(\Request::file('fotos'), \Request::input());
  });

  Route::delete('admin/fileupload/{action?}', function ($action=null) {
      $upload = new \lslucas\Files\Upload();
      $upload->remove(\Request::input('file'));
  });


});

Route::any('api/{base?}', 'ApiController@base');

// Logging in and out
Route::get('/auth/login', 'Auth\AuthController@getLogin');
Route::post('/auth/login', 'Auth\AuthController@postLogin');
Route::get('/auth/logout', 'Auth\AuthController@getLogout');
Route::controllers([
	//'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('validateLogin/{login}/{password}', function ($login, $password) {
    $logged_in = Auth::once(['email' => $login, 'password' => $password]);

    if ( ! $logged_in) {
        throw new Exception('not logged in');
    }
});

Route::get('storage/{part1?}/{part2?}/{part3?}/{part4?}/{part5?}', function ($part1, $part2=null, $part3=null, $part4=null, $part5=null) {

/*
    $img = storage_path() . '/app/'. str_replace('storage/', '', Request::path());
    $image = Image::make($img);
    return Response::make($image);
*/
    $path = $part1;

    if ( !empty($part2) )
        $path .= '/'.$part2;

    if ( !empty($part3) )
        $path .= '/'.$part3;

    if ( !empty($part4) )
        $path .= '/'.$part4;

    $upload = new \lslucas\Files\Retrieve();
    return $upload->get($path);
});

/**
 * Utils
 * **/
Route::get('newCsrfToken', function() {
    //$encrypter = app('Illuminate\Encryption\Encrypter');
    return csrf_token();
});
