<?php 
namespace app\core;

use App\Core\App;
use app\core\exception\BaseException;

/*
|--------------------------------------------------------------------------
| Core Controller
|--------------------------------------------------------------------------
| The controller class is used to connect to the view and models
|
*/

class Controller 
{
    /**
     * Contains the model to be loaded
     * 
     * @var string $load
     */
    public $load;

    /**
     * This is used to display only views, 
     * which are in the app/resources/views folder
     * 
     * @param string $view 
     * @param array  $params
     */
    public function onlyView($view,$params = [])
    {
        echo App::$view->renderOnlyView($view,$params);
    }

    /**
     * This function is used to display views and their layout templates which are 
     * in the app/resources/views/layouts folder 
     * and their views which are in the app/resources/views folder.
     * 
     */
    public function layoutView($view,$params = [])
    {
        echo App::$view->renderLayoutView($view,$params);
    }

    /**
     * To connect to the model class
     * 
     * @param string $name_model
     */
    public function model($name_model)
    {
        try 
        {
            $this->load = "app\models\\" . $name_model . ".php";
            if(file_exists($this->load))
            {
                require_once $this->load;
                $this->load = str_replace(".php","",$this->load);
                return new $this->load;
            }
            else 
            {
                throw new BaseException("Model $name_model not found",404);
            }
        }
        catch(BaseException $e)
        {
            BaseException::getException($e);
        }

    }

}