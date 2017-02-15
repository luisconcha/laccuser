<?php
Route::group(['as' => 'laccuser.'], function () {

    Route::group(['prefix' => 'admin'], function () {
        Route::resource('/users', 'UsersController', ['except' => ['show']]);
        Route::get('users/{id}', ['as' => 'users.destroy', 'uses' => 'UsersController@destroy']);
        Route::get('user-advanced-search', ['as' => 'advanced.users.search', 'uses' => 'UsersController@advancedSearch']);
        Route::get('users-detail/{id}', ['as' => 'users.detail', 'uses' => 'UsersController@detail']);
    });
    Route::get('user/password-edit', ['as' => 'user_password.edit', 'uses' => 'UsersPasswordController@edit']);
    Route::put('user/password-update', ['as' => 'user_password.update', 'uses' => 'UsersPasswordController@update']);



    Route::get('email-verification/error', ['as' => 'email-verification.error', 'uses'=>'UserConfirmationController@getVerificationError']);
    Route::get('email-verification/check/{token}', ['as' => 'email-verification.check', 'uses'=>'UserConfirmationController@getVerification']);

});
