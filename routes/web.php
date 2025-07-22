<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-upload', function () {
    return view('test_upload');
});

Route::get('/admin-test', function () {
    return view('admin_test');
});
