<?php

use App\Http\Controllers\EmailController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('/', [TestController::class, 'test']);


Route::controller(EmailController::class)->group(function(){
    //upsert for both update and insert
    Route::post('/emails', 'upsert');
    //get specific email
    Route::post('/emails/{id}', 'get');
    //delete specific email
    Route::post('/emails/delete/{id}', 'delete');
});


Route::controller(GroupController::class)->group(function(){
    //upsert for both update and insert
    Route::post('/groups', 'upsert');
    //get specific group
    Route::post('/groups/{id}', 'get');
    //delte specific group
    Route::post('/groups/delete/{id}', 'delete');
    //add specific emails to specific group
    Route::post('/groups/{id}/add', 'addEmails');
});



