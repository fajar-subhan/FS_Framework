<?php 
namespace app\Core;

/*
|--------------------------------------------------------------------------
| HTTP Request
|--------------------------------------------------------------------------
| 
| Take a request from HTTP easily using this class
|
*/

class Request 
{
    /** 
     * Query string url
     * 
     * @var array $url
    */
    protected $url = [];

    /**
     * This method is used to retrieve the query string from the url
     * 
     * @return array $url
     */
    public function getURL()
    {

        if(isset($_GET['url']))
        {
            $this->url = rtrim($_GET['url'],'/');

            $this->url = filter_var($this->url,FILTER_SANITIZE_URL);
            
            $this->url = explode('/',$this->url);
        }
        
        return $this->url;
    }
}