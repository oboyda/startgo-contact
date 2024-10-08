<?php 
namespace SGC\Route;

class Base {

    private $route_base = '';
    private $routes = [];

    protected $response_code;
    protected $response_errors = [];
    protected $response_data = null;
    protected $response_meta = [];

    public function __construct($route_base=''){
        $this->route_base = $route_base;
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes(){
        foreach($this->routes as $route){
            register_rest_route(
                $this->route_base, 
                $route['path'], 
                $route['args']
            );
        }
    }

    public function addRoute($path, $args=[]){
        $route_key = str_replace('/', '_', $this->route_base . $path);
        $this->routes[$route_key] = [
            'path' => $path,
            'args' => array_merge([
                'permission_callback' => '__return_true'
            ], $args)
        ];
    }

    public function setResponseCode($code){
        $this->response_code = $code;
    }
    public function addResponseError($error, $code=null){
        $this->response_errors[] = $error;
        if(isset($code)){
            $this->response_code = $code;
        }
    }
    public function setResponseData($data){
        $this->response_data = $data;
    }
    public function addResponseData($key, $data){
        if(!isset($this->response_data)){
            $this->response_data = [];
        }
        $this->response_data[$key] = $data;
    }
    public function addResponseMeta($key, $meta){
        $this->response_meta[$key] = $meta;
    }

    public function getResponse(){
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