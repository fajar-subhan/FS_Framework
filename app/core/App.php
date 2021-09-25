<?php 
namespace app\core;

class App 
{
    /**
     * Object Router 
     * 
     * @var object $router
     */
    protected $router;

    /**
     * Object Request
     * 
     * @var object $request
     */
    protected $request;

    /**
     * Object App 
     * 
     * @var object $app
     */
    public static $app;

    /**
     * Object view 
     * 
     * @var object $view
     */
    public static $view;

    /**
     * @var object controller
     */
    public static $controller;

    public function __construct($request)
    {
        self::$controller   = new Controller();
        self::$view         = new View();
        self::$app          = $this;
        $this->request      = $request;
        $this->router       = new Router($this->request);
    }

    public function run()
    {
        $this->router->resolve();
    }
}