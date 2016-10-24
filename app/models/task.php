<?php

/*
 * Askareen malliluokka
 */

class Task extends BaseModel {

    public $id, $name, $description, $oblivious_id, $done, $deadline, $added, $categories;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_deadline', 'validate_description');
    }

    /*
     * Palauttaa kaikki käyttäjän askareet
     */

    public static function all($oblivious_id) {
        $query = DB::connection()->prepare('SELECT * FROM Task WHERE oblivious_id = :oblivious_id');
        $query->execute(array('oblivious_id' => $oblivious_id));
        $rows = $query->fetchAll();
        $tasks = array();

        foreach ($rows as $row) {
            $tasks[] = new Task(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'oblivious_id' => $row['oblivious_id'],
                'done' => $row['done'],
                'deadline' => $row['deadline'],
                'added' => $row['added'],
                'categories' => Task::find_categories($row['id'])
            ));
        }

        return $tasks;
    }

    /*
     * Palauttaa askareen ilman sen kategorioita. Metodi on tarpeellinen kun listataan kaikki askarekategoriaan kuuluvat askareet 
     */

    public static function findWithoutCategories($id) {

        $query = DB::connection()->prepare('SELECT * FROM Task WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $task = new Task(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'oblivious_id' => $row['oblivious_id'],
                'done' => $row['done'],
                'deadline' => $row['deadline'],
                'added' => $row['added']
            ));
        }

        return $task;
    }

    /*
     * Palauttaa id:n mukaisen askareen
     */

    public static function find($id) {

        $query = DB::connection()->prepare('SELECT * FROM Task WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $task = new Task(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'oblivious_id' => $row['oblivious_id'],
                'done' => $row['done'],
                'deadline' => $row['deadline'],
                'added' => $row['added'],
                'categories' => Task::find_categories($id)
            ));
        }

        return $task;
    }

    /*
     * Palauttaa askareen kaikki askarekategoriat
     */

    public static function find_categories($TaskId) {

        $query = DB::connection()->prepare('SELECT taskcategory_id FROM TaskCategoryUnion WHERE task_id = :TaskId');
        $query->execute(array('TaskId' => $TaskId));
        $rows = $query->fetchAll();

        $categories = array();

        foreach ($rows as $row) {
            $categories[] = TaskCategory::find($row['taskcategory_id']);
        }

        return $categories;
    }

    /*
     * Tallettaa askareen tietokantatauluun Task
     * Kutsuu metodia save_categories($id)
     */

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO Task (name, description, oblivious_id, deadline, added) VALUES (:name, :description, :oblivious_id, :deadline, NOW()) RETURNING id');
        $query->execute(array('name' => $this->name, 'description' => $this->description, 'oblivious_id' => $this->oblivious_id, 'deadline' => $this->deadline));
        $row = $query->fetch();
        Task::save_categories($row['id']);

        $this->id = $row['id'];
    }

    /*
     * Tallettaa askareen ja kategoriat liitostauluun TaskCategoryUnion
     */

    public function save_categories($task_id) {

        foreach ($this->categories as $category) {
            $categoryid = $category['id'];
            $query = DB::connection()->prepare('INSERT INTO TaskCategoryUnion (task_id, taskcategory_id) VALUES (:task_id, :categoryid)');
            $query->execute(array('task_id' => $task_id, 'categoryid' => $categoryid));
        }
    }

    /*
     * Päivittää askareen tiedot tietokantatauluun Task. Kutsuu metodia save_categories($id).
     */

    public function update() {
        $query = DB::connection()->prepare('UPDATE Task SET id = :id, name = :name, description = :description, deadline = :deadline WHERE id = :id RETURNING id');
        $query->execute(array('id' => $this->id, 'name' => $this->name, 'description' => $this->description, 'deadline' => $this->deadline));
        $row = $query->fetch();
        Task::save_categories($row['id']);

        $this->id = $row['id'];
    }

    /*
     * Päivittää tietokantataulun Task sarakkeen done arvoksi TRUE id:n mukaiselle riville
     */

    public function done() {
        $query = DB::connection()->prepare('UPDATE Task SET done = TRUE WHERE id = :id RETURNING id');
        $query->execute(array('id' => $this->id));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    /*
     * Poistaa yksittäisen Askareen tietokantataulusta Task
     * Poistaa kaikki id:n mukaiset ilmentymät Askareesta ja siihen liittyvistä Askarekategorioista tietokantataulusta TaskCategoryUnion
     */

    public function destroy() {

        $query = DB::connection()->prepare('DELETE FROM Task WHERE id = :id RETURNING id');
        $query1 = DB::connection()->prepare('DELETE FROM TaskCategoryUnion WHERE Task_id = :id');
        $query1->execute(array('id' => $this->id));
        $query->execute(array('id' => $this->id));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    /*
     * Poistaa tietokantataulusta TaskCategoryUnion Askareen jonka kategoria on $category_id:n mukainen
     */

    public function destroy_category($category_id) {
        $query = DB::connection()->prepare('DELETE FROM TaskCategoryUnion WHERE task_id = :task_id AND taskcategory_id = :category_id RETURNING task_id');
        $query->execute(array('task_id' => $this->id, 'category_id' => $category_id));
        $row = $query->fetch();

        $this->id = $row['task_id'];
    }

    /*
     * Parametrin $name validointimetodi
     */

    public function validate_name() {

        $errors = array();

        if ($this->name == '' || $this->name == null) {
            $errors[] = 'Tyhjä nimi ei kelpaa!';
        } else if (strlen($this->name) < 2) {
            $errors[] = 'Nimen pituuden oltava vähintään kaksi merkkiä!';
        } else if (strlen($this->name) > 50) {
            $errors[] = 'Nimi voi olla maksimissaan pituudeltaan 50 merkkiä';
        }

        return $errors;
    }

    /*
     * Parametrin $deadline validointimetodi
     */

    public function validate_deadline() {

        $errors = array();

        if ($this->deadline < date("Y-m-d")) {
            $errors[] = 'deadline ei voi olla menneisyydessä';
        }

        return $errors;
    }

    /*
     * Parametrin $description validointimetodi
     */

    public function validate_description() {

        $errors = array();

        if (strlen($this->description) > 500) {
            $errors[] = 'Kuvaus voi olla maksimissaan pituudeltaan 500 merkkiä';
        }

        return $errors;
    }

}
