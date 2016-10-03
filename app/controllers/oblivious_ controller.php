<?php

class ObliviousController extends BaseController {

    public static function login() {

        View::make('login.html');
    }

    public static function handle_login() {
        $params = $_POST;

        $obl = Oblivious::authenticate($params['name'], $params['password']);

        if (!$obl) {
            View::make('login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'name' => $params['name']));
        } else {
            $_SESSION['user'] = $obl->id;

            Redirect::to('/', array('message' => 'Kirjautuminen onnistui, ' . $obl->name . '!'));   
        }
    }

}
