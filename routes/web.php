<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('export/export-guru', [ExportController::class, 'export_guru'])
    ->name('export.guru');

Route::get('export/export-presensi-guru', [ExportController::class, 'export_presensi_guru'])
    ->name('export.presensi.guru');

Route::get('export/export-siswa', [ExportController::class, 'export_siswa'])
    ->name('export.siswa');

Route::get('export/export-presensi-siswa', [ExportController::class, 'export_presensi_siswa'])
    ->name('export.presensi.siswa');
