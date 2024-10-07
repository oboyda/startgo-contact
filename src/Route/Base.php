<?php 
namespace SGC\Route;

class Base {

    private $routes = [];

    protected $response_status = 200;
    protected $response_errors = [];
    protected $response_data = [];

    public function __construct(){
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes(){
        foreach($this->routes as $route){
            register_rest_route($route['base'], $route['path'], $route['args']);
        }
    }

    protected function addRoute($base, $path, $args=[]){
        $route_key = str_replace('/', '_', $base . $path);
        $this->routes[$route_key] = [
            'base' => $base,
            'path' => $path,
            'args' => array_merge([
                'permission_callback' => '__return_true'
            ], $args)
        ];
    }

    protected function respond(){

        
    }
}