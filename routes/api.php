<?php

use App\Http\Controllers\EmailController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('/', [TestController::class, 'test']);


Route::post('/send-mail', [EmailController::class, 'sendMail']);

Route::controller(EmailController::class)->prefix('emails')->group(function () {
    //upsert for both update and insert
    Route::post('/', 'upsert');
    //get specific email
    Route::post('/{id}', 'get');
    //delete specific email
    Route::post('/delete/{id}', 'delete');
});


Route::controller(GroupController::class)->prefix('groups')->group(function () {
    //upsert for both update and insert
    Route::post('/', 'upsert');
    //get specific group
    Route::post('/{id}', 'get');
    //delte specific group
    Route::post('/delete/{id}', 'delete');

    //add specific emails to specific group
    Route::post('/{id}/add', 'addEmails');
    Route::post('/{id}/remove', 'removeEmails');
    Route::post('/{id}/get', 'getEmails');
});
