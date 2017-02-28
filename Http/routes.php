<?php
Route::group( [ 'as' => 'laccuser.', 'middleware' => [ 'auth', config( 'laccuser.middleware.isVerified' ) ] ], function () {
		Route::group( [ 'prefix' => 'admin', 'middleware' => 'can:user-admin' ], function () {
				Route::resource( '/users', 'UsersController', [ 'except' => [ 'show' ] ] );
				Route::get( 'users/{id}', [ 'as' => 'users.destroy', 'uses' => 'UsersController@destroy' ] );
				Route::get( 'user-advanced-search', [ 'as' => 'advanced.users.search', 'uses' => 'UsersController@advancedSearch' ] );
				Route::get( 'users-detail/{id}', [ 'as' => 'users.detail', 'uses' => 'UsersController@detail' ] );

				//Trash
				Route::group(['prefix' => 'trashed', 'as' => 'trashed.'], function (){
						Route::resource( 'users', 'Trashs\UsersTrashController',
							[ 'except' => [ 'show','create', 'store','edit', 'update', 'destroy' ] ]  );
						Route::get( 'users/{id}', [ 'as' => 'users.restore', 'uses' => 'Trashs\UsersTrashController@update' ] );
				});

				//Roles
				Route::group(['as' => 'role.'], function (){
						Route::resource( 'roles', 'Roles\RolesController', 	[ 'except' => [ 'show','destroy' ] ]  );
            Route::get( 'roles-delete/{id}', [ 'as' => 'roles.destroy', 'uses' => 'Roles\RolesController@destroy' ] );
				});

		} );
		Route::get( 'user/password-edit', [ 'as' => 'user_password.edit', 'uses' => 'UsersPasswordController@edit' ] );
		Route::put( 'user/password-update', [ 'as' => 'user_password.update', 'uses' => 'UsersPasswordController@update' ] );
		Route::get( 'email-verification/error', [ 'as' => 'email-verification.error', 'uses' => 'UserConfirmationController@getVerificationError' ] );
		Route::get( 'email-verification/check/{token}', [ 'as' => 'email-verification.check', 'uses' => 'UserConfirmationController@getVerification' ] );

} );
