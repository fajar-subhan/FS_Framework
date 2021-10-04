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
     * @var object database
     */
    public static $database;

    public static $env;

    public function __construct($setup)
    {
        self::$view         = new View();
        self::$app          = $this;
        self::$env          = $setup['env'];
        $this->request      = $setup['request'];
        $this->router       = new Router($this->request);
    }

    public function run()
    {
        $this->router->resolve();
    }
}