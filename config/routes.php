<?php

$routes->get('/tasks', function()     {
    TaskController::index();
});

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/task_list', function() {
    HelloWorldController::task_list();
});

    $routes->get('/task/:id', function($id) {
    TaskController::show($id);
});

$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/modify', function() {
    HelloWorldController::modify();
});
