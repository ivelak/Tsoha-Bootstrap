<?php

class TaskCategoryController extends BaseController{
    
    public static function index() {
        self::check_logged_in();
        $taskCategories = TaskCategory::all(parent::get_user_logged_in()->id);


        View::make('task_categories/category_list.html', array('taskCategories' => $taskCategories));
    }

    public static function show($id) {
        self::check_logged_in();
        $taskCategory = TaskCategory::find($id);

        View::make('task_categories/category_show.html', array('taskCategory' => $taskCategory));
    }

    public static function edit($id) {
        self::check_logged_in();
        $taskCategory = TaskCategory::find($id);
        View::make('task/category_edit.html', array('attributes' => $taskCategory));
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'name' => $params['name']
        );

        $taskCategory = new TaskCategory($attributes);
        $errors = $taskCategory->errors();

        if (count($errors) > 0) {
            View::make('task/category_edit.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $taskCategory->update();

            Redirect::to('/task/' . $task->id, array('message' => 'Askaretta on muokattu onnistuneesti!'));
        }
    }

    public static function create() {
        self::check_logged_in();
        View::make('task/new_category.html');
    }

    public static function store() {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'name' => $params['name'],
            'description' => $params['description'],
            'oblivious_id' => parent::get_user_logged_in()->id
        );

        $taskCategory = new TaskCategory($attributes);
        $errors = $taskCategory->errors();

        if (count($errors) == 0) {

            $taskCategory->save();

            Redirect::to('/task/' . $task->id, array('message' => 'Uusi askare lisätty!'));
        } else {
//Kint::dump($errors);
            View::make('task/new_category.html', array('errors' => $errors, 'attributes' => $attributes));
        }

//Kint::dump($params);
    }

    public static function destroy($id) {
        self::check_logged_in();
        $taskCategory = new TaskCategory(array('id' => $id));
        $taskCategory->destroy();

        Redirect::to('/task', array('message' => 'Askare on poistettu onnistuneesti!'));
    }
}
