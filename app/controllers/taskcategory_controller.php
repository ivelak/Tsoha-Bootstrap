<?php

/*
 * Kontrolleriluokka Askarekategorialle
 */

class TaskCategoryController extends BaseController {
    /*
     * Palauttaa näkymän Askarekategorioiden listaussivulle
     */

    public static function index() {
        self::check_logged_in();
        $taskCategories = TaskCategory::all(parent::get_user_logged_in()->id);

        View::make('task_categories/category_list.html', array('taskCategories' => $taskCategories));
    }

    /*
     * Palauttaa näkymän yksittäisen Askarekategorian esittelysivulle
     */

    public static function show($id) {
        self::check_logged_in();
        $taskCategory = TaskCategory::find($id);

        View::make('task_categories/category_show.html', array('taskCategory' => $taskCategory));
    }

    /*
     * Palauttaa näkymän Askarekategorian muokkaussivulle
     */

    public static function edit($id) {
        self::check_logged_in();
        $taskCategory = TaskCategory::find($id);
        View::make('task_categories/category_edit.html', array('attributes' => $taskCategory));
    }

    /*
     * Päivittää attribuuttien tiedot Askarekategorialle ja uudelleenohjaa Askarekategorian esittelysivulle
     * Palauttaa näkymän Askarekategorian muokkaussivulle mikäli muokkauksessa esiintyi validointivirheitä
     */

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
            View::make('task_categories/category_edit.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $taskCategory->update();

            Redirect::to('/task/category/' . $taskCategory->id, array('message' => 'Askarekategoriaa on muokattu onnistuneesti!'));
        }
    }

    /*
     * Palauttaa näkymän uuden Askarekategorian luontisivulle
     */

    public static function create() {
        self::check_logged_in();
        View::make('task_categories/category_new.html');
    }

    /*
     * Tallettaa parametrien mukaiset tiedot uudelle Askarekategorialle ja uudelleenohjaa Askarekategorialistaan
     * Palauttaa näkymän uuden Askarekategorian luontisivulle mikäli lisäyksessä ilmeni validointivirheitä
     */

    public static function store() {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'name' => $params['name'],
            'oblivious_id' => parent::get_user_logged_in()->id
        );

        $taskCategory = new TaskCategory($attributes);
        $errors = $taskCategory->errors();

        if (count($errors) == 0) {

            $taskCategory->save();

            Redirect::to('/task/category', array('message' => 'Uusi askare lisätty!'));
        } else {
            View::make('task_categories/category_new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    /*
     * Poistaa id:n mukaisen Askarekategorian ja uudelleenohjaa Askarekategorioiden listaussivulle
     */

    public static function destroy($id) {
        self::check_logged_in();
        $taskCategory = new TaskCategory(array('id' => $id));
        $taskCategory->destroy();

        Redirect::to('/task/category', array('message' => 'Askarekategoria on poistettu onnistuneesti!'));
    }

}
