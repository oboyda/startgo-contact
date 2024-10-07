<?php 
namespace SGC\Type;

class Base {

    protected $id = 0;
    protected $post = null;
    protected $post_data = [];
    protected $post_meta = [];
    protected $props_config = [];
    protected $props_mutated = [];

    public function __construct($post=null, $props_config=[]){
        $this->setPost($post);
        $this->setPropsConfig(array_merge([
                'post_title' => [
                    'type' => 'data'
                ],
                'post_content' => [
                    'type' => 'data'
                ],
                'post_excerpt' => [
                    'type' => 'data'
                ]
            ], $props_config));
        $this->populateProps();
    }

    private function setPost($post){
        $this->post = is_a($post, 'WP_Post') ? $post : (is_int($post) ? get_post($post) : $post);
        $this->id = $this->post ? $this->post->ID : 0;
    }

    private function setPropsConfig($props_config=[]){
        $this->props_config = array_map(function($config){
            return $config = array_merge([
                'label' => '',
                'type' => 'data',
                'input_type' => null,
                'cast' => 'string',
                'default' => null
            ], $config);
        }, $props_config);
    }

    private function castProp($value, $cast='string'){
        switch($cast){
            case 'integer':
                $value = intval($value);
            case 'array_integer':
                $value = is_array($value) ? array_map(function($item){ return $this->castProp($value, 'integer'); }, $value) : [];
                break;
            case 'float':
                $value = floatval($value);
            case 'array_float':
                $value = is_array($value) ? array_map(function($item){ return $this->castProp($value, 'float'); }, $value) : [];
                break;
        }
        return $value;
    }

    private function populateProps(){
        if(!$this->id) return;
        foreach($this->props_config as $prop => $config){
            switch($config['type']){
                case 'data':
                    $this->post_data[$prop] = property_exists($this->post, $prop) ? $this->post[$prop] : null;
                    $this->post_data[$prop] = $this->castProp($this->post_data[$prop], $config['cast']);
                    break;
                case 'meta':
                    $this->post_meta[$prop] = get_post_meta($this->id, $prop, true);
                    $this->post_meta[$prop] = $this->castProp($this->post_meta[$prop], $config['cast']);
                    break;
            }
        }
    }

    public function getPropConfig($prop, $k=null){
        $config = isset($props_config[$prop]) ? $props_config[$prop] : null;
        return $k ? (($config && isset($config[$k])) ? $config[$k] : null) : $config;
    }

    public function set($prop, $value=null){
        switch($this->getPropConfig($prop, 'type')){
            case 'data':
                if(isset($this->post_data[$prop]) || $this->post_data[$prop] !== $value){
                    $this->props_mutated[] = $prop;
                }
                $this->post_data[$prop] = $value;
                break;
            case 'meta':
                if(isset($this->post_meta[$prop]) || $this->post_meta[$prop] !== $value){
                    $this->props_mutated[] = $prop;
                }
                $this->post_meta[$prop] = $value;
                break;
        }
    }
    public function get($prop, $default=null){
        switch($this->getPropConfig($prop, 'type')){
            case 'data':
                $value = $this->post_data[$prop] = $value;
                break;
            case 'meta':
                $value = $this->post_meta[$prop] = $value;
                break;
        }
        $config_default = $this->getPropConfig($prop, 'default');
        return isset($value) ? $value : (isset($default) ? $default : $config_default);
    }
    public function delete($prop){
        $this->set($prop, null);
    }
    public function save(){
        if($this->id){
            wp_update_post(
                array_merge(
                    array_filter($this->post_data, function($item, $p){
                        return in_array($p, $this->props_mutated);
                    }, ARRAY_FILTER_USE_BOTH), 
                    ['meta_input' => array_filter($this->post_meta, function($item, $p){
                        return in_array($p, $this->props_mutated);
                    }, ARRAY_FILTER_USE_BOTH)],
                    ['ID' => $this->id]
                ),
                false
            );
        }else{
            $this->id = wp_insert_post(
                array_merge(
                    $this->post_data, 
                    ['meta_input' => $this->post_meta]
                ),
                false
            );
        }
    }

    public function getPermalink(){
        return $this->id ? $this->get_permalink($this->id) : false;
    }

    public function toArray(){
        return array_merge(
            $this->post_data, 
            $this->post_meta, [
                'permalink' => $this->getPermalink()
            ]
        );
    }
}