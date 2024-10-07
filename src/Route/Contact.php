<?php 
namespace SGC\Route;

class Contact extends Base {

    const ROUTE_BASE = 'sgc/v1/contact';

    public function __construct(){
        parent::__construct();

        $this->addRoute(self::ROUTE_BASE, '/get', [
            'methods' => 'GET',
            'callback' => [$this, 'get']
        ]);
        $this->addRoute(self::ROUTE_BASE, '/find', [
            'methods' => 'GET',
            'callback' => [$this, 'find']
        ]);
        $this->addRoute(self::ROUTE_BASE, '/post', [
            'methods' => 'POST',
            'callback' => [$this, 'post']
        ]);
        $this->addRoute(self::ROUTE_BASE, '/put', [
            'methods' => 'PUT',
            'callback' => [$this, 'put']
        ]);
        $this->addRoute(self::ROUTE_BASE, '/delete', [
            'methods' => 'DELETE',
            'callback' => [$this, 'delete']
        ]);

    }

    public function get($req){

        try {
            $id = $req->get_param('id');

            

        } catch(\Exception $e) {
            
        }


    }
    public function find($req){
    }
    public function post($req){
    }
    public function put($req){
    }
    public function delete($req){
    }
}