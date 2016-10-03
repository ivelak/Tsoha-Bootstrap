<?php

class TaskController extends BaseController {

    public static function index() {
        $tasks = Task::all();

        View::make('task/task_list.html', array('tasks' => $tasks));
    }

    public static function show($id) {

        $task = Task::find($id);

        View::make('task/task_show.html', array('task' => $task));
    }

    public static function create() {
        View::make('task/new.html');
    }

    public static function store() {
        $params = $_POST;

        $attributes = array(
            'name' => $params['name'],
            'description' => $params['description']
        );

        $task = new Task($attributes);
        $errors = $task->errors();

        if (count($errors) == 0) {

            $task->save();

            Redirect::to('/task/' . $task->id, array('message' => 'Uusi askare lisätty!'));
        } else {
            //Kint::dump($errors);
            View::make('task/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }

        //Kint::dump($params);
    }

}
