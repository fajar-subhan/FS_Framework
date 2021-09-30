<?php 
namespace app\models;

use app\core\Model;

class M_Welcome extends Model
{
    private $db;

    public function __construct()
    {
        $this->db = parent::__construct();
    }

    public function _getData()
    {   
        $result = [];

        

    }
}