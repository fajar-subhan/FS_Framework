<?php 
namespace app\core;

use App\Core\App;

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
     * This is to put the default layout template to be used
     * 
     * @var string $layout
     */
    public $layout = "template";

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

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
        echo App::$view->renderView($view,$params);
    }

}