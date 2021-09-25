<?php 
namespace app\core;

use app\core\Request;
use app\core\exception\BaseException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| 
| This is the class that is used to create a url route to its place
|
*/

class Router 
{
    /**
     * Used to save the default controller
     * 
     * @var string $controller
     */
    private $controller = "welcome";

    /**
     * Used to save the default method
     * 
     * @var string $method 
     */
    private $method = "index";

    /**
     * Used to store default parameters
     * 
     * @var array $params 
     */
    private $params = [];

    /**
     * HTTP Request
     * 
     * @var object $request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function url()
    {
        return $this->request->getURL();
    }
    
    public function resolve()
    {
        try 
        {
            if(!empty($this->url()))
            {
                $this->controller   = ucfirst($this->url()[0]);
    
                if(file_exists('app/http/controllers/' . $this->controller . '.php'))
                {
                    $this->controller = $this->controller;
                }
                else 
                {
                    throw new BaseException("File app/http/controllers/{$this->url()[0]}.php not found",404);
                }
            }

            $this->controller = "app\http\controllers\\" . $this->controller;

            if(class_exists($this->controller))
            {
                $this->controller = new $this->controller;
            }
            else 
            {
                throw new BaseException("Class {$this->url()[0]} not found",404);
            }

            if(!empty($this->url()[1]))
            {
                if(method_exists($this->controller,$this->url()[1]))
                {
                    $this->method = $this->url()[1];
                }
                else 
                {
                    throw new BaseException("Method {$this->url()[1]} not found",404);
                }
            }
            else 
            {
                $this->method = $this->method;
            }
            
            if(!empty($this->url()) && is_array($this->url()))
            {
                /** 
                 *  Delete the url controller and method so that 
                 *  later you can get the third segment params 
                 * 
                 *  Example > controller/method/params 
                 */
                $url = $this->url();
                unset($url[0]);
                unset($url[1]);
                
                $this->params = array_values($url);
            }       

            // var_dump($this->params);die();


            call_user_func_array([$this->controller,$this->method],$this->params);
        }
        catch(BaseException $e)
        {
            BaseException::getException($e);
        }
    }


}