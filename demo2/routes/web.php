<?php


Route::get('/', function () {
    return view('index');
});

Route::get('list','MembersController@getList');
Route::post('add','MembersController@postAdd');
Route::get('edit/{id}','MembersController@getEdit');
Route::post('edit/{id}','MembersController@postEdit');

Route::get('delete/{id}','MembersController@getDel');



