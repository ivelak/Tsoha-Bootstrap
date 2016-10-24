<?php

/*
 * Kontrolleriluokka Käyttäjäluokalle
 */
class ObliviousController extends BaseController {

    /*
     * Palauttaa näkymän sisäänkirjautumissivulle
     */
    public static function login() {

        View::make('login.html');
    }

    /*
     * Käsittelee sisäänkirjautumisen ja uudelleenohjaa etusivulle
     * Palauttaa sisäänkirjautumissivulle mikäli kirjautumisessa ilmeni virheitä
     */
    public static function handle_login() {
        $params = $_POST;

        $obl = Oblivious::authenticate($params['name'], $params['password']);

        if (!$obl) {
            View::make('oblivious/login.html', array('errors' => 'Väärä käyttäjätunnus tai salasana!', 'name' => $params['name']));
        } else {
            $_SESSION['user'] = $obl->id;

            Redirect::to('/', array('message' => 'Kirjautuminen onnistui, ' . $obl->name . '!'));
        }
    }

    /*
     * Uloskirjaa kirjautuneen käyttäjän ja uudelleenohjaa etusivulle
     */
    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }

    /*
     * Palauttaa näkymän uuden Käyttäjän luomissivulle
     */
    public static function signin() {
        
        View::make('oblivious/signin.html');
    }

    /*
     * Tallettaa Käyttäjäoliolle attribuuttien mukaiset tiedot ja uudelleenohjaa etusivulle
     * Palauttaa näkymän uuden Käyttäjän luomissivulle mikäli uuden käyttäjän luomisessa ilmeni validointivirheitä
     */
    public static function create() {
        $params = $_POST;

        $attributes = array(
            'name' => $params['name'],
            'password' => $params['password']
        );

        $oblivious = new Oblivious($attributes);
        $oblivious->add_newname_validator();
        $errors = $oblivious->errors();

        if (count($errors) == 0) {
            $oblivious->save();
            $_SESSION['user'] = $oblivious->id;

            Redirect::to('/', array('message' => 'Uuden käyttäjätilin luonti onnistui, ' . $oblivious->name . '!'));
        } else {

            View::make('oblivious/signin.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

}
