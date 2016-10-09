<?php

class TaskCategory extends BaseModel {

    public $id, $name, $oblivious_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all($oblivious_id) {
        $query = DB::connection()->prepare('SELECT * FROM TaskCategory WHERE oblivious_id = :oblivious_id');
        $query->execute(array('oblivious_id' => $oblivious_id));
        $rows = $query->fetchAll();
        $taskCategories = array();

        foreach ($rows as $row) {
            $taskCategories[] = new TaskCategory(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'oblivious_id' => $row['oblivious_id']
            ));
        }
        return $taskCategories;
    }

    public static function find($id) {

        $query = DB::connection()->prepare('SELECT * FROM TaskCategory WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $taskCategory = new TaskCategory(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'oblivious_id' => $row['oblivious_id']
            ));
        }

        return $taskCategory;
    }

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO TaskCategory (name, oblivious_id) VALUES (:name, :oblivious_id) RETURNING id');
        $query->execute(array('name' => $this->name, 'oblivious_id' => $this->oblivious_id));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }

    public function update() {

        $query = DB::connection()->prepare('UPDATE TaskCategory SET id = :id, name = :name, WHERE id = :id RETURNING id');
        $query->execute(array('id' => $this->id, 'name' => $this->name));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }
    
    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM TaskCategory WHERE id = :id RETURNING id');
        $query->execute(array('id'=> $this->id));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }

}
