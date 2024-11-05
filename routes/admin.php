<?php

Route::group(['prefix' => 'admin'], function(){

    Route::group(['prefix' => 'dashboard'], function(){
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.index');
    });



});
