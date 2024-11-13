<?php

Route::group(['prefix' => 'admin'], function(){

    Route::group(['prefix' => 'dashboard'], function(){
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard.index');
    });

    Route::group(['prefix' => 'sekolah'], function(){
        Route::get('/', [App\Http\Controllers\Admin\SekolahController::class, 'index'])->name('admin.sekolah.index');

        Route::post('/get-kabupaten', [App\Http\Controllers\Admin\SekolahController::class, 'getKabupaten'])->name('admin.sekolah.get-kabupaten');
        Route::post('/get-kecamatan', [App\Http\Controllers\Admin\SekolahController::class, 'getKecamatan'])->name('admin.sekolah.get-kecamatan');
        Route::post('/get-kelurahan', [App\Http\Controllers\Admin\SekolahController::class, 'getKelurahan'])->name('admin.sekolah.get-kelurahan');

        Route::get('/sinkronisasi-data-sekolah', [App\Http\Controllers\Admin\SekolahController::class, 'sinkroninasiDataSekolah'])->name('admin.sekolah.sinkronisasi-data-sekolah');
        Route::get('/lengkapi-data-sekolah/data/{id}', [App\Http\Controllers\Admin\SekolahController::class, 'lengkapiDataSekolahData'])->name('admin.sekolah.lengkapi-data-sekolah.data');
        Route::post('/lengkapi-data-sekolah', [App\Http\Controllers\Admin\SekolahController::class, 'lengkapiDataSekolah'])->name('admin.sekolah.lengkapi-data-sekolah');

        Route::post('/', [App\Http\Controllers\Admin\SekolahController::class, 'store'])->name('admin.sekolah.store');
        Route::post('/update', [App\Http\Controllers\Admin\SekolahController::class, 'update'])->name('admin.sekolah.update');
        Route::get('/edit/{id}', [App\Http\Controllers\Admin\SekolahController::class, 'edit'])->name('admin.sekolah.edit');
        Route::get('/detail/{id}', [App\Http\Controllers\Admin\SekolahController::class, 'detail'])->name('admin.sekolah.detail');
        Route::get('/destroy/{id}', [App\Http\Controllers\Admin\SekolahController::class, 'destroy'])->name('admin.sekolah.destroy');
    });

    Route::prefix('master-jenjang-sekolah')->group(function(){
        Route::get('/', [App\Http\Controllers\Admin\MasterJenjangSekolahController::class, 'index'])->name('admin.master-jenjang-sekolah.index');
        Route::get('/detail/{id}', [App\Http\Controllers\Admin\MasterJenjangSekolahController::class, 'show'])->name('admin.master-jenjang-sekolah.detail');
        Route::post('',[App\Http\Controllers\Admin\MasterJenjangSekolahController::class, 'store'])->name('admin.master-jenjang-sekolah.store');
        Route::get('/edit/{id}', [App\Http\Controllers\Admin\MasterJenjangSekolahController::class, 'edit'])->name('admin.master-jenjang-sekolah.edit');
        Route::post('/update',[App\Http\Controllers\Admin\MasterJenjangSekolahController::class, 'update'])->name('admin.master-jenjang-sekolah.update');
        Route::get('/destroy/{id}', [App\Http\Controllers\Admin\MasterJenjangSekolahController::class, 'destroy'])->name('admin.master-jenjang-sekolah.destroy');
    });

    Route::prefix('master-kecepatan-internet')->group(function(){
        Route::get('/', [App\Http\Controllers\Admin\MasterKecepatanInternetController::class, 'index'])->name('admin.master-kecepatan-internet.index');
        Route::get('/detail/{id}', [App\Http\Controllers\Admin\MasterKecepatanInternetController::class, 'show'])->name('admin.master-kecepatan-internet.detail');
        Route::post('',[App\Http\Controllers\Admin\MasterKecepatanInternetController::class, 'store'])->name('admin.master-kecepatan-internet.store');
        Route::get('/edit/{id}', [App\Http\Controllers\Admin\MasterKecepatanInternetController::class, 'edit'])->name('admin.master-kecepatan-internet.edit');
        Route::post('/update',[App\Http\Controllers\Admin\MasterKecepatanInternetController::class, 'update'])->name('admin.master-kecepatan-internet.update');
        Route::get('/destroy/{id}', [App\Http\Controllers\Admin\MasterKecepatanInternetController::class, 'destroy'])->name('admin.master-kecepatan-internet.destroy');
    });

    Route::prefix('master-kurikulum')->group(function(){
        Route::get('/', [App\Http\Controllers\Admin\MasterKurikulumController::class, 'index'])->name('admin.master-kurikulum.index');
        Route::get('/detail/{id}', [App\Http\Controllers\Admin\MasterKurikulumController::class, 'show'])->name('admin.master-kurikulum.detail');
        Route::post('',[App\Http\Controllers\Admin\MasterKurikulumController::class, 'store'])->name('admin.master-kurikulum.store');
        Route::get('/edit/{id}', [App\Http\Controllers\Admin\MasterKurikulumController::class, 'edit'])->name('admin.master-kurikulum.edit');
        Route::post('/update',[App\Http\Controllers\Admin\MasterKurikulumController::class, 'update'])->name('admin.master-kurikulum.update');
        Route::get('/destroy/{id}', [App\Http\Controllers\Admin\MasterKurikulumController::class, 'destroy'])->name('admin.master-kurikulum.destroy');
    });
});
