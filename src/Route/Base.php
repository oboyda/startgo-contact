<?php 
namespace SGC\Route;

class Base {

    private $routes = [];

    protected $response_code;
    protected $response_errors = [];
    protected $response_data = null;
    protected $response_meta = [];

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

    protected function setResponseCode($code){
        $this->response_code = $code;
    }
    protected function addResponseError($error, $code=null){
        $this->response_errors[] = $error;
        if(isset($code)){
            $this->response_code = $code;
        }
    }
    protected function setResponseData($data){
        $this->response_data = $data;
    }
    protected function addResponseData($key, $data){
        if(!isset($this->response_data)){
            $this->response_data = [];
        }
        $this->response_data[$key] = $data;
    }
    protected function addResponseMeta($key, $meta){
        $this->response_meta[$key] = $meta;
    }

    protected function getResponse(){
        if(!isset($this->response_code)){
            $this->response_code = empty($this->response_errors) ? 200 : 400;
        }
        $response_body = [
            // 'code' => $this->response_code,
            'data' => $this->response_data,
            'meta' => $this->response_meta
        ];
        return empty($this->response_errors) 
            ? new \WP_REST_Response($response_body, $this->response_code)
            : new \WP_Error($this->response_code, implode(' ', $this->response_errors), $response_body);
    }
}