<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of askare
 *
 * @author antti
 */
class Task extends BaseModel {

    public $id, $name, $description, $oblivious_id, $done;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Task');
        $query->execute();
        $rows = $query->fetchAll();
        $tasks = array();

        foreach ($rows as $row) {
            $tasks[] = new Task(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'oblivious_id' => $row['oblivious_id'],
                'done' => $row['done']
            ));
        }
        return $tasks;
    }

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
                'done' => $row['done']
            ));
        }

        return $task;
    }

    public function save() {

        $query = DB::connection()->prepare('INSERT INTO Task (name, description) VALUES (:name, :description) RETURNING id');
        $query->execute(array('name' => $this->name, 'description' => $this->description));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }

}
