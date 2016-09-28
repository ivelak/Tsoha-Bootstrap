<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('home.html');
    }

    public static function sandbox() {
        $roskat = Task::find(1);
        $askareet = Task::all();
        
        Kint::dump($askareet);
        Kint::dump($roskat);
    }
    
    public static function task_list() {
        View::make('suunnitelmat/task_list.html');
    }
    
    public static function task_show() {
        View::make('suunnitelmat/task_show.html');
    }
    
    public static function login() {
        View::make('suunnitelmat/login.html');
    }
    
    public static function modify() {
        View::make('suunnitelmat/modify.html');
    }

}
