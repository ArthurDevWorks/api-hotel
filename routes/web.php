<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Version Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
