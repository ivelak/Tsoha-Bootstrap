<?php

/*
 * Askareen kontrolleriluokka
 */
class TaskController extends BaseController {

    /*
     * Palauttaa näkymän Askarelistalle
     */
    public static function index() {
        self::check_logged_in();
        $tasks = Task::all(parent::get_user_logged_in()->id);


        View::make('task/task_list.html', array('tasks' => $tasks));
    }

    /*
     * Palauttaa näkymän yksittäisen Askareen esittelysivulle
     */
    public static function show($id) {
        self::check_logged_in();
        $task = Task::find($id);

        View::make('task/task_show.html', array('task' => $task));
    }

    /*
     * Palauttaa näkymän Askareen muokkaussivulle
     */
    public static function edit($id) {
        self::check_logged_in();
        $task = Task::find($id);
        $categories_all = TaskCategory::all(parent::get_user_logged_in()->id);
        $categories_task = $task->categories;
        $categories_result = array();

        foreach ($categories_all as $cat_all) {
            if (!in_array($cat_all, $categories_task)) {
                $categories_result[] = $cat_all;
            }
        }

        View::make('task/task_edit.html', array('attributes' => $task, 'categories' => $categories_result));
    }
    
    /*
     * Merkitsee Askareen suoritetuksi ja uudelleenohja Askareiden listaussivulle
     */
    public static function make_done($id) {
        self::check_logged_in();
        $task = Task::find($id);
        $task->done();
        $message='Askare ' . $task->name . ' merkitty suoritetuksi!';
        
        Redirect::to('/task', array('task'=>$task, 'message' => $message));
    }

    /*
     * Päivittää attribuuttien mukaiset tiedot Askareelle ja uudelleenohjaa Askareen esittelysivulle
     * Kutsuu metodia destroy_category($id, $categoryid)
     * Palauttaa näkymän muokkaussivulle mikäli parametreissä on virheitä
     */
    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;

        if (array_key_exists('categories', $params)) {
            $categories = $params['categories'];
        } else {
            $categories = array();
        }

        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'description' => $params['description'],
            'deadline' => $params['deadline'],
            'categories' => array()
        );

        foreach ($categories as $category) {
            $attributes['categories'][] = $category;
        }

        $task = new Task($attributes);
        $errors = $task->errors();

        if (count($errors) > 0) {

            $task->categories = Task::find_categories($task->id);
            $categories_all = TaskCategory::all(parent::get_user_logged_in()->id);
            $categories_task = $task->categories;
            $categories_result = array();

            foreach ($categories_all as $cat_all) {
                if (!in_array($cat_all, $categories_task)) {
                    $categories_result[] = $cat_all;
                }
            }
            foreach ($categories_task as $category) {
            $attributes['categories'][] = $category;
        }

            
            View::make('task/task_edit.html', array('errors' => $errors, 'attributes' => $attributes, 'categories' => $categories_result));
        } else {
            $task->update();

            Redirect::to('/task/' . $task->id, array('message' => 'Askaretta on muokattu onnistuneesti!'));
        }
    }

    /*
     * Palauttaa näkymän uuden Askareen luontisivulle
     */
    public static function create() {
        self::check_logged_in();
        View::make('task/task_new.html', array('categories' => TaskCategory::all(parent::get_user_logged_in()->id)));
    }

    /*
     * Tallettaa attribuuttien mukaiset tiedot uudelle Askareelle ja uudelleenohjaa Askareen esittelysivulle
     * Palauttaa näkymän uuden Askareen lisäämiselle mikäli lisäämisessä on ollut validointivirheitä
     */
    public static function store() {
        self::check_logged_in();
        $params = $_POST;

        if (array_key_exists('categories', $params)) {
            $categories = $params['categories'];
        } else {
            $categories = array();
        }

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
            
            View::make('task/task_new.html', array('errors' => $errors, 'attributes' => $attributes, 'categories' => TaskCategory::all(parent::get_user_logged_in()->id)));
        }

    }

    /*
     * Poistaa id:n mukaisen askareen ja uudelleenohjaa Askarelistauksen näkymään
     */
    public static function destroy($id) {
        self::check_logged_in();
        $task = Task::find($id);
        $task->destroy();

        $message = 'Askare ' . $task->name . ' on poistettu';
        
        Redirect::to('/task', array('message' => $message));
    }

    /*
     * Poistaa askarekategorian askareelta. Metodia kutsutaan metodissa update($id)
     */
    public static function destroy_category($id, $category_id) {
        self::check_logged_in();
        $task = Task::find($id);
        $task->destroy_category($category_id);
        $task = Task::find($id);

        TaskController::edit($id);
    }

}
