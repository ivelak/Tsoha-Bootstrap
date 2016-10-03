<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('home.html');
    }

    public static function sandbox() {
        $doom = new Task(array(
            'name' => 'T',
            'description' => 'eilen'
            
        ));
        $errors = $doom->errors();

        Kint::dump($errors);

        
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
