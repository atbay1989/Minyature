<?php

use App\Controllers\HomeController;
use App\Controllers\MessageController;

$app->get('/', HomeController::class . ':index');
$app->post('/messages', MessageController::class . ':store')->setName('messages.store');
$app->get('/messages/{uuid}', MessageController::class . ':show')->setName('messages.show');