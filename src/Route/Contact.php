<?php 
namespace SGC\Route;

class Contact extends Base {

    const ROUTE_BASE = 'sgc/v1/contact';

    public function __construct(){
        parent::__construct();

        $this->addRoute(self::ROUTE_BASE , '/get', [
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

        $id = (int)$req->get_param('id');

        try {
            if(!$id){
                throw new \Exception('Required params missing', 400);
            }
            $type_contact = new \SGC\Type\Contact($id);
            if(!$type_contact->getId()){
                throw new \Exception('Type not found', 404);
            }
            $this->setResponseData($type_contact->toArray());

        } catch(\Exception $e) {
            $this->addResponseError($e->getMessage(), $e->getCode());
        }

        return $this->getResponse();
    }

    public function find($req){

        $params = array_merge([
            'paged' => 1
        ], $req->get_params());

        $params = array_filter($params, function($value, $param){
            return in_array($param, [
                'paged',
                //...
            ]);
        }, ARRAY_FILTER_USE_BOTH);

        try {
            $query_params = array_merge($params, [
                'posts_per_page' => 10,
                'post_status' => 'publish',
                'post_type' => 'sgc_contact',
                'paged' => (int)$params['paged']
            ]);
            $posts_query = new \WP_Query($query_params);

            $this->setResponseData(array_map(function($post){
                return (new \SGC\Type\Contact($post))->toArray();
            }, $posts_query->posts));

            $this->addResponseMeta('total', $posts_query->found_posts);

        } catch(\Exception $e) {
            $this->addResponseError($e->getMessage(), $e->getCode());
        }

        return $this->getResponse();
    }

    public function post($req){

        $params = $req->get_params();

        $params = array_filter($params, function($value, $param){
            return in_array($param, [
                'paged',
                //...
            ]);
        }, ARRAY_FILTER_USE_BOTH);

        try {
            $query_params = array_merge($params, [
                'posts_per_page' => 10,
                'post_status' => 'publish',
                'post_type' => 'sgc_contact'
            ]);
            $posts_query = new \WP_Query($query_params);

            $this->setResponseData(array_map(function($post){
                return (new \SGC\Type\Contact($post))->toArray();
            }, $posts_query->posts));

            $this->addResponseMeta('total', $posts_query->found_posts);

        } catch(\Exception $e) {
            $this->addResponseError($e->getMessage(), $e->getCode());
        }

        return $this->getResponse();
    }

    public function put($req){
    }

    public function delete($req){
    }
}