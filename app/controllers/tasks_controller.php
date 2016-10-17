<?php

class TaskController extends BaseController {

    public static function index() {
        self::check_logged_in();
        $tasks = Task::all(parent::get_user_logged_in()->id);


        View::make('task/task_list.html', array('tasks' => $tasks));
    }

    public static function show($id) {
        self::check_logged_in();
        $task = Task::find($id);

        View::make('task/task_show.html', array('task' => $task));
    }

    public static function edit($id) {
        self::check_logged_in();
        $task = Task::find($id);
        View::make('task/edit.html', array('attributes' => $task));
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'description' => $params['description'],
            'deadline' => $params['deadline']
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
        self::check_logged_in();
        View::make('task/new.html', array('categories' => TaskCategory::all(parent::get_user_logged_in()->id)));
    }

    public static function store() {
        self::check_logged_in();
        $params = $_POST;

        $categories = $params['categories'];

        $attributes = array(
            'name' => $params['name'],
            'description' => $params['description'],
            'oblivious_id' => parent::get_user_logged_in()->id,
            'deadline' => $params['deadline'],
            'categories' => array()
        );

        foreach ($categories as $category) {
            $attributes['categories'][] = $category;
        }

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
        self::check_logged_in();
        $task = new Task(array('id' => $id));
        $task->destroy();

        Redirect::to('/task', array('message' => 'Askare on poistettu onnistuneesti!'));
    }

}
