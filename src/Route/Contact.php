<?php 
namespace SGC\Route;

class Contact extends Base {

    public function __construct(){
        parent::__construct('sgc/v1/contact');

        $this->addRoute('/get', [
            'methods' => 'GET',
            'callback' => [$this, 'get']
        ]);
        $this->addRoute('/find', [
            'methods' => 'GET',
            'callback' => [$this, 'find']
        ]);
        $this->addRoute('/insert', [
            'methods' => 'POST',
            'callback' => [$this, 'insert']
        ]);
        $this->addRoute('/update', [
            'methods' => 'PUT',
            'callback' => [$this, 'update']
        ]);
        $this->addRoute('/delete', [
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

    public function insert($req){

        $params = array_merge([
            'data' => []
        ], $req->get_params());

        $params['data'] = array_merge([
            'customer_first_name' => '',
            'customer_last_name' => '',
            'customer_email' => ''
        ], $params['data']);

        try {
            $type_contact = new \SGC\Type\Contact();
            $type_contact->setProps($params['data']);
            $type_contact->set('title', sprintf(__('Contact from %s', 'sgc'), $params['data']['customer_email']));
            $type_contact->set('post_status', 'publish');
            $type_contact->save();

            $this->setResponseData($type_contact->toArray());

        } catch(\Exception $e) {
            $this->addResponseError($e->getMessage(), $e->getCode());
        }

        return $this->getResponse();
    }

    public function update($req){

        $params = array_merge([
            'id' => 0,
            'data' => []
        ], $req->get_params());

        try {
            if(!$params['id']){
                throw new \Exception('Required params missing', 400);
            }
            if(!$params['data']){
                throw new \Exception('No data to update', 400);
            }

            $type_contact = new \SGC\Type\Contact((int)$params['id']);
            if(!$type_contact->getId()){
                throw new \Exception('Type not found', 404);
            }

            $type_contact->setProps($params['data']);
            $type_contact->save();

            $this->setResponseData($type_contact->toArray());

        } catch(\Exception $e) {
            $this->addResponseError($e->getMessage(), $e->getCode());
        }

        return $this->getResponse();
    }

    public function delete($req){

        $params = array_merge([
            'id' => 0
        ], $req->get_params());

        try {
            if(!$params['id']){
                throw new \Exception('Required params missing', 400);
            }

            $type_contact = new \SGC\Type\Contact((int)$params['id']);
            if(!$type_contact->getId()){
                throw new \Exception('Type not found', 404);
            }

            $this->setResponseData($type_contact->toArray());

            if($type_contact->destroy()){
                $this->addResponseMeta('deleted', true);
            }

        } catch(\Exception $e) {
            $this->addResponseError($e->getMessage(), $e->getCode());
        }

        return $this->getResponse();
    }
}