<?php
namespace src\controllers;

use \core\Controller;

class HomeController extends Controller 
{   
    private $loggedUser;

    public function __construct()
    {
        $this->redirect('/login');
    }

    public function index() 
    {
        $this->render('home', ['nome' => 'Bonieky']);
    }

    

}