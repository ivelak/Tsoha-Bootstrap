<?php

$routes->get('/task', function() {
    TaskController::index();
});



$routes->post('/task', function() {
    TaskController::store();
});

$routes->get('/task/new', function() {
    TaskController::create();
});

$routes->get('/task/:id', function($id) {
    TaskController::show($id);
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



$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/modify', function() {
    HelloWorldController::modify();
});
