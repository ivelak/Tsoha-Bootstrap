<?php

/*
 * Askarekategorian malliluokka
 */

class TaskCategory extends BaseModel {

    public $id, $name, $oblivious_id, $tasks;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name');
    }

    /*
     * Palauttaa kaikki kirjautuneen käyttäjän askarekategoriat
     */

    public static function all($oblivious_id) {
        $query = DB::connection()->prepare('SELECT * FROM TaskCategory WHERE oblivious_id = :oblivious_id');
        $query->execute(array('oblivious_id' => $oblivious_id));
        $rows = $query->fetchAll();
        $taskCategories = array();

        foreach ($rows as $row) {
            $taskCategories[] = new TaskCategory(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'oblivious_id' => $row['oblivious_id'],
                'tasks' => TaskCategory::find_tasks($row['id'])
            ));
        }
        return $taskCategories;
    }

    /*
     * Palauttaa id:n mukaisen askareen tietokantataulusta TaskCategory
     */

    public function find($id) {

        $query = DB::connection()->prepare('SELECT * FROM TaskCategory WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $taskCategory = new TaskCategory(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'oblivious_id' => $row['oblivious_id'],
                'tasks' => TaskCategory::find_tasks($id)
            ));
        }
        return $taskCategory;
    }

    /*
     * Palauttaa taulukon askareita jotka kuuluvat id:n mukaiselle askarekategorialle tietokantataulusta TaskCategoryUnion
     */

    public static function find_tasks($TaskCategoryId) {

        $query = DB::connection()->prepare('SELECT task_id FROM TaskCategoryUnion WHERE taskcategory_id = :TaskCategoryId');
        $query->execute(array('TaskCategoryId' => $TaskCategoryId));
        $rows = $query->fetchAll();

        $tasklist = array();

        foreach ($rows as $row) {
            $tasklist[] = Task::findWithoutCategories($row['task_id']);
        }

        return $tasklist;
    }

    /*
     * Tallettaa askarekategorian tietokantatauluun TaskCategory
     */

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO TaskCategory (name, oblivious_id) VALUES (:name, :oblivious_id) RETURNING id');
        $query->execute(array('name' => $this->name, 'oblivious_id' => $this->oblivious_id));
        $row = $query->fetch();

        $this->id = $row['id'];
    }

    /*
     * Päivittää tiedot id:n mukaiselle askarekategorialle tietokantataulussa TaskCategory
     */

    public function update() {

        $query = DB::connection()->prepare('UPDATE TaskCategory SET id = :id, name = :name WHERE id = :id RETURNING id');
        $query->execute(array('id' => $this->id, 'name' => $this->name));
        $row = $query->fetch();

        $this->id = $row['id'];
    }

    /*
     * Poistaa tietokantataulusta TaskCategory id:n mukaisen askarekategorian
     * Poistaa tietokantataulusta TaskCategoryUnion kaikki id:n mukaiset askarekategoriat ja niihin kuuluvat askareet
     */

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM TaskCategory WHERE id = :id RETURNING id');
        $query1 = DB::connection()->prepare('DELETE FROM TaskCategoryUnion WHERE Taskcategory_id = :id');
        $query1->execute(array('id' => $this->id));
        $query->execute(array('id' => $this->id));
        $row = $query->fetch();

        $this->id = $row['id'];
    }

    /*
     * Parametrin $name validointimetodi
     */

    public function validate_name() {
        $errors = array();
        if ($this->name == '' || $this->name == null) {
            $errors[] = 'Tyhjä nimi ei kelpaa';
        } else if (strlen($this->name) < 2) {
            $errors[] = 'Nimen pituuden oltava vähintään kaksi merkkiä';
        } else if (strlen($this->name) > 50) {
            $errors[] = 'Nimi voi olla maksimissaan pituudeltaan 50 merkkiä';
        }

        return $errors;
    }

}
