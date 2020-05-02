<?php
namespace src\handlers;
use \src\models\User;

class LoginHandler
{
    public static function checkLogin()
    {
        if (!empty($_SESSION['token'])) {

            $token = $_SESSION['token'];

            $data = User::select()->where('token', $token)->execute();

            /* 
                Necessário fazer isso, pois por algum motivo que eu ainda não sei e 
                nem estou com vontade de descobrir no momento o array do banco está 
                vindo com mais de uma posição

            */
            $data = $data[0];

            if (count($data) > 0) {
                $loggedUser = new User();
                $loggedUser->id = $data['id'];
                $loggedUser->email = $data['email'];
                $loggedUser->name = $data['name'];

                return $loggedUser;

            }
        }

        return false;
    } // END checkLogin()

    public static function verifyLogin($email, $password)
    {   
        $user = User::select()->where('email', $email)->one();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $token = md5(time().rand(0, 9999).time());

                User::update()
                    ->set('token', $token)
                    ->where('email', $email)
                ->execute();

                return $token;
            }   
        }

        return false;
    } // END verifyLogin()

    public static function emailExists($email) 
    {
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    } // END emailExists

    public static function addUser($name, $email, $password, $birthdate) 
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $token = md5(time().rand(0, 9999).time());

        User::insert([
            'email' => $email,
            'password' => $hash,
            'name' => $name, 
            'birthdate' => $birthdate,
            'token' => $token
        ])->execute();

        return $token;
    }
}
