<?php

class Task extends BaseModel {

    public $id, $name, $description, $oblivious_id, $done, $deadline, $added, $categories;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name');
    }

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

    public static function find($id) {

        $query = DB::connection()->prepare('SELECT * FROM Task WHERE id = :id LIMIT 1');

        # SELECT t.*, tc.* FROM Task as t, TaskCategory as tc, TaskTaskCategory as ttc
        #   WHERE tc.id = ttc.cat_id AND t.id = ttc.task_id AND t.id = :id
        # [{ t.id: taskin id
        #   ...
        #   t.added: taskin luontiaika
        #   tc.name: askarekategorian nimi
        #   ... ja muut askarekategorian
        #  }, toinen kategoria]
        # 

        $query->execute(array('id' => $id));
        $row = $query->fetch();

        # SELECT tc.* FROM Task as t, TaskCategory as tc, TaskTaskCategory as ttc
        #   WHERE tc.id = ttc.cat_id AND t.id = ttc.task_id AND t.id = :id

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

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO Task (name, description, oblivious_id, deadline, added) VALUES (:name, :description, :oblivious_id, :deadline, NOW()) RETURNING id');
        $query->execute(array('name' => $this->name, 'description' => $this->description, 'oblivious_id' => $this->oblivious_id, 'deadline' => $this->deadline));
        $row = $query->fetch();
        Task::save_categories($row['id']);
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }

    public function save_categories($task_id) {
        
        foreach ($this->categories as $category) {
            $categoryid = $category['id'];
            $query = DB::connection()->prepare('INSERT INTO TaskCategoryUnion (task_id, taskcategory_id) VALUES (:task_id, :categoryid)');
            $query->execute(array('task_id' => $task_id, 'categoryid' => $categoryid));
        }
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Task SET id = :id, name = :name, description = :description, deadline = :deadline WHERE id = :id RETURNING id');
        $query->execute(array('id' => $this->id, 'name' => $this->name, 'description' => $this->description, 'deadline' => $this->deadline));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Task WHERE id = :id RETURNING id');
        $query1 = DB::connection()->prepare('DELETE FROM TaskCategoryUnion WHERE Task_id = :id');
        $query1->execute(array('id' => $this->id));                
        $query->execute(array('id' => $this->id));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }
    public function destroy_category($category_id) {
        $query = DB::connection()->prepare('DELETE FROM TaskCategoryUnion WHERE task_id = :task_id AND taskcategory_id = :category_id RETURNING task_id');
        $query->execute(array('task_id'=> $this->id, 'category_id' => $category_id));
        $row = $query->fetch();
        
        $this->id = $row['task_id'];
    }

    public function validate_name() {
        $errors = array();
        if ($this->name == '' || $this->name == null) {
            $errors[] = 'Tyhjä nimi ei kelpaa!';
        } else if (strlen($this->name) < 2) {
            $errors[] = 'Nimen pituuden oltava vähintään kaksi merkkiä!';
        }

        return $errors;
    }

}
