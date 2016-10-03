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
            $errors[] = 'Tyhjä nimi ei kelpaa!';
        } else if (strlen($this->name) < 2) {
            $errors[] = 'Nimen pituuden oltava vähintään kaksi merkkiä!';
        }

        return $errors;
    }

    public function validate_password() {
        $errors = array();
        if ($this->name == '' || $this->name == null) {
            $errors[] = 'Salasana ei voi olla tyhjä!';
        } else if (strlen($this->name) < 5) {
            $errors[] = 'Salasanan pituuden oltava vähintään viisi merkkiä!';
        }

        return $errors;
    }

}
