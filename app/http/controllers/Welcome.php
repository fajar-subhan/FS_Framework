<?php 
namespace app\http\controllers;

use app\core\Controller;

class Welcome extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('M_Welcome');
    }

    public function index()
    {
        $data = $this->model->_getData();
        echo "<pre>";
       print_r($data);


        return $this->onlyView('welcome',[]);
    }
}