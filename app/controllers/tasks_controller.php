<?php

class TaskController extends BaseController {
    

    public static function index() {
        $tasks = Task::all(parent::get_user_logged_in()->id);
        

        View::make('task/task_list.html', array('tasks' => $tasks));
    }

    public static function show($id) {

        $task = Task::find($id);

        View::make('task/task_show.html', array('task' => $task));
    }

    public static function edit($id) {
        $task = Task::find($id);
        View::make('task/edit.html', array('attributes' => $task));
    }

    public static function update($id) {
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'description' => $params['description']
        );

        $task = new Task($attributes);
        $errors = $task->errors();

        if (count($errors) > 0) {
            View::make('task/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $task->update();

            Redirect::to('/task/' . $task->id, array('message' => 'Askaretta on muokattu onnistuneesti!'));
        }
    }

    public static function create() {
        View::make('task/new.html');
    }

    public static function store() {
        $params = $_POST;

        $attributes = array(
            'name' => $params['name'],
            'description' => $params['description'],
            'oblivious_id' => parent::get_user_logged_in()->id
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

    public static function destroy($id) {
        $task = new Task(array('id' => $id));
        $task->destroy();

        Redirect::to('/task', array('message' => 'Askare on poistettu onnistuneesti!'));
    }

}
