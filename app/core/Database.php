<?php 

namespace app\core;

use app\core\exception\BaseException;
use PDO;
use PDOException;

/*
|--------------------------------------------------------------------------
| Core Database
|--------------------------------------------------------------------------
| The database controller is the parent class to be able to connect to the database
|
*/

class Database 
{

    /**
     * The active PDO connection
     * 
     * @var pdo $pdo
     */
    protected $pdo;

    /**
     * The Singleton's instance is stored in a static field. 
     * 
     * @var boolean $instance 
     */
    protected static $instance = null; 

    protected function __construct($_env = null)
    {
        try 
        {
            if(is_null($_env))
            {
                $_env = App::$env;
            }

            $this->pdo = new PDO($_env['DB_DSN'],$_env['DB_USER'],$_env['DB_PASS']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
            return $this->pdo;
        }
        catch(PDOException $e)
        {
            BaseException::getException($e);
        }
    }

    /**
     * This is the static method that controls the access to the singleton
     * instance. On the first run, it creates a singleton object and places it
     * into the static field. On subsequent runs, it returns the client existing
     * object stored in the static field.
     * 
     * @return object 
     */
    public static function getInstance($_env = [])
    {
        if(is_null(self::$instance))
        {
            return self::$instance = new self($_env);
        }

        return self::$instance;
    }
}

