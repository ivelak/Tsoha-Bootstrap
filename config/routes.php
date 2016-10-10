<?php

$routes->get('/task/category', function() {
    TaskCategoryController::index();    
});

$routes->get('/task/category/:id', function($id) {
    TaskCategoryController::show($id);
});

$routes->get('/login', function() {
    ObliviousController::login();
});

$routes->post('/login', function() {
    ObliviousController::handle_login();
});

$routes->post('/logout', function() {
    ObliviousController::logout();
});

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

$routes->get('/task/:id/edit', function($id) {
    TaskController::edit($id);
});

$routes->post('/task/:id/edit', function($id) {
    TaskController::update($id);
});

$routes->post('/task/:id/destroy', function($id) {
    TaskController::destroy($id);
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
