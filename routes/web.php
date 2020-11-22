<?php

use App\Http\Middleware\DatatableListMiddleware;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('admin/organs');
});

Auth::routes();

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');
Route::get('change_password', 'Admin\UsersController@changePasswordView')->middleware('auth');
Route::post('change_password', 'Admin\UsersController@changePassword')->middleware('auth');
Route::post('change_organ', 'Admin\UsersController@changeOrgan')->middleware('auth');
Route::get('profile', 'Admin\UsersController@profile')->middleware('auth');


Route::group(['prefix'=>'admin', 'middleware'=>'auth'], function(){
    Route::group(['prefix'=>'users'], function(){
        Route::get('/', 'Admin\UsersController@index')->name('users.index')->middleware(AdminMiddleware::class);
        Route::any('list', 'Admin\UsersController@getList')->name('users.list')->middleware(AdminMiddleware::class);
        Route::post('store', 'Admin\UsersController@store')->name('users.store')->middleware(AdminMiddleware::class);
        Route::get('{userId}/organs', 'OrganizationController@index')->name('user.organs');
        Route::get('{userId}/organs_data', 'OrganizationController@getUserList')->name('user.organs_data');
        Route::post('{userId}/add_organ', 'OrganizationController@addUserOrgan')->name('user.add_organ')->middleware(AdminMiddleware::class);
    });

    Route::group(['prefix'=>'organs'], function() {
        Route::get('/', 'OrganizationController@index');
        Route::get('list', 'OrganizationController@getList')->name('organs.list');
        Route::post('store', 'OrganizationController@store')->name('organs.store')->middleware(AdminMiddleware::class);
        Route::get('{organId}/events', 'EventsController@index')->name('organ.events');

        Route::get('{userOrganId}/roles', 'RolesController@index')->name('user.roles')->middleware(AdminMiddleware::class);
        Route::get('{userOrganId}/roles_data', 'RolesController@getUserList')->name('user.roles_data')->middleware(AdminMiddleware::class);
        Route::post('{userOrganId}/add_role', 'RolesController@addUserRole')->name('user.add_role')->middleware(AdminMiddleware::class);
    });
    Route::group(['prefix'=>'events'], function() {
        Route::get('/', 'EventsController@index')->name('events.index');
        Route::get('list', 'EventsController@getList')->name('events.list');
        Route::post('store', 'EventsController@store')->name('events.store');
        Route::get('{eventId}/join', 'EventsController@join')->name('event.join');
        Route::get('{eventId}/participants', 'EventsController@participants')->name('event.participants');
        Route::get('{eventId}/participants_data', 'EventsController@getParticipants')->name('event.participants_data');
        Route::post('start', 'EventsController@start')->name('event.start');
        Route::post('stop', 'EventsController@stop')->name('event.stop');
    });

    Route::group(['prefix'=>'participants'], function(){
        Route::post('{eventId}/active', 'ParticipantsController@active')->name('participant.active');
    });

});

Route::group(['prefix'=>'pages'], function(){
    Route::get('404', 'PageController@page')->name('404');
    Route::get('504', 'PageController@page')->name('504');
});
