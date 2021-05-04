<?php

Route::redirect('/', '/login');

Route::redirect('/home', '/admin');

Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');

    Route::resource('permissions', 'PermissionsController');

    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');

    Route::resource('roles', 'RolesController');

    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');

    Route::resource('users', 'UsersController');
    // Route::resource('restaurants', 'RestaurantController');
    Route::get('restaurants', 'RestaurantController@index')->name('restaurants.index');
    Route::post('restaurants/store', 'RestaurantController@store')->name('restaurants.store');
    Route::post('restaurants/update', 'RestaurantController@update')->name('restaurants.update');
    Route::post('restaurants/edit', 'RestaurantController@editData')->name('restaurants.edit');
    Route::post('restaurants/show', 'RestaurantController@show')->name('restaurants.show');
    Route::delete('restaurants/mass-destroy', 'RestaurantController@massDestroy')->name('restaurants.massDestroy');
    Route::delete('restaurants/destroy', 'RestaurantController@destroy')->name('restaurants.Destroy');
});
