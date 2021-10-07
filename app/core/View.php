<?php 
namespace app\core;

use app\core\exception\BaseException;
use Exception;

class View 
{
    /**
     * This function is used to display views and their layout templates which are 
     * in the app/resources/views/layouts folder 
     * and their views which are in the app/resources/views folder.
     * 
     * @param string $view
     * @param array $params
     */
    public function renderLayoutView($view,$params = [])
    {
        /**
         * This is used to display only views, 
         * which are in the app/resources/views folder
         * 
         * @var @viewContent
         */
        $viewContent    = $this->renderOnlyView($view,$params);

        /**
         * This is to display layouts content
         * which is in the app/resources/views/layouts folder.
         * So that later we can use the layout without having to 
         * retype the header, body, footer and other views
         * 
         * @var @layoutContent
         */
        $layoutContent  = $this->layoutContent();

        echo str_replace("{{content}}",$viewContent,$layoutContent);
    }

    /**
     * This function serves to direct as well as enter the layout file
     * 
     */
    public function layoutContent()
    {
        /* Default Layout : Template */
        try 
        {
            $layout = Controller::$layout;

            if(empty($layout))
            {
                throw new Exception('Layout not found',404);
            }
            else 
            {
                require_once  str_replace("\\","/",ROOT_PATH) . "/app/resources/views/layouts/$layout.php"; 
                $template =  ob_get_clean();

                return $template;
            }
        }
        catch(Exception $e)
        {
            BaseException::getException($e);
        }
    }

    /**
     * This method is used to display only the view without any layout
     * 
     * @param string $view 
     * @param array  $params
     */
    public function renderOnlyView($view,$params = [])
    {
        /**
         * Convert key data array into variable
         * 
         * @link https://www.javatpoint.com/php-dollar-doubledollar 
         * @var $$key double dolar
         */
        foreach($params as $key => $value)
        {
            $$key = $value;  
        }

        ob_start();
        require_once str_replace("\\","/",ROOT_PATH) . "/app/resources/views/$view.php";
        $data = ob_get_clean();

        return $data;
    }
}