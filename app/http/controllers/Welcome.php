<?php 
namespace app\http\controllers;

use app\core\Controller;

class Welcome extends Controller
{
    public function index()
    {
        return $this->onlyView('welcome',[]);
    }
}