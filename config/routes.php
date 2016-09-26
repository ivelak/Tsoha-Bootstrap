<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
  
  $routes->get('/task_list', function() {
  HelloWorldController::task_list();
  });
  
  $routes->get('/task/1', function() {
  HelloWorldController::task_show();
  });

  $routes->get('/login', function() {
  HelloWorldController::login();
  });
  
  $routes->get('/modify', function() {
  HelloWorldController::modify();
  });