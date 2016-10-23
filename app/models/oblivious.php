<?php

class Oblivious extends BaseModel {

    public $id, $name, $password;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_password');
    }

    public static function authenticate($name, $password) {

        $query = DB::connection()->prepare('SELECT * FROM Oblivious WHERE name = :name AND password = :password LIMIT 1');
        $query->execute(array('name' => $name, 'password' => $password));
        $row = $query->fetch();
        if ($row) {
            $obl = new Oblivious(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'password' => $row['password']
            ));
        } else {
            return null;
        }
        return $obl;
    }
    
    public function add_newname_validator() {
        $this->validators[]='validate_newname';
    }

    public static function allnames() {
        $query = DB::connection()->prepare('SELECT name FROM Oblivious');
        $query->execute();
        $rows = $query->fetchAll();
        $names=array();
        foreach ($rows as $row){
            $names[]=$row['name'];
        }
        return $names;
        
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Oblivious (name, password) VALUES (:name, :password) RETURNING id');
        $query->execute(array('name' => $this->name, 'password' => $this->password));
        $row = $query->fetch();

        $this->id = $row['id'];
        
    }

    public static function find($id) {

        $query = DB::connection()->prepare('SELECT * FROM Oblivious WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $obl = new Oblivious(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'password' => $row['password']
            ));
        }

        return $obl;
    }

    public function validate_name() {
        $errors = array();
        if ($this->name == '' || $this->name == null) {
            $errors[] = 'Käyttäjätunnus ei voi olla tyhjä!';
        } else if (strlen($this->name) < 3) {
            $errors[] = 'Käyttäjätunnuksen on oltava vähintään kolmen merkin pituinen!';
        }

        return $errors;
    }

    public function validate_newname() {
        $names = Oblivious::allnames();
        $errors = array();

        foreach ($names as $name) {
            if ($this->name == $name) {
                $errors[] = 'Antamasi käyttäjätunnus on jo käytössä! Keksi uusi!';
            }
        }
        return $errors;
    }

    public function validate_password() {
        $errors = array();
        if ($this->password == '' || $this->password == null) {
            $errors[] = 'Salasana ei voi olla tyhjä!';
        } else if (strlen($this->password) < 5) {
            $errors[] = 'Salasanan pituuden oltava vähintään viisi merkkiä!';
        }

        return $errors;
    }

}
