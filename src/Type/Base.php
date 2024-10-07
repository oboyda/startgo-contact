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
        $this->setPropsConfig($props_config);
        $this->populateProps();
    }

    private function setPost($post){
        $this->post = is_a($post, 'WP_Post') ? $post : (is_int($post) ? get_post($post) : $post);
        $this->id = $this->post ? $this->post->ID : 0;
    }

    private function setPropsConfig($props_config=[]){
        $this->props_config = array_map(function($config){
            return array_merge([
                'key' => null,
                'label' => '',
                'type' => 'data',
                'input_type' => null,
                'cast' => 'string',
                'required' => false,
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
            $key = isset($config['key']) ? $config['key'] : $prop;
            switch($config['type']){
                case 'data':
                    $this->post_data[$key] = property_exists($this->post, $key) ? $this->post->$key : null;
                    $this->post_data[$key] = $this->castProp($this->post_data[$key], $config['cast']);
                    break;
                case 'meta':
                    $this->post_meta[$key] = get_post_meta($this->id, $key, true);
                    $this->post_meta[$key] = $this->castProp($this->post_meta[$key], $config['cast']);
                    break;
            }
        }
    }

    public function getPropConfig($prop, $item=null, $default=null){
        $prop_config = isset($this->props_config[$prop]) ? $this->props_config[$prop] : null;
        $config = $item ? (($prop_config && isset($prop_config[$item])) ? $prop_config[$item] : null) : $prop_config;
        return isset($config) ? $config : $default;
    }

    public function set($prop, $value=null){
        $key = $this->getPropConfig($prop, 'key', $prop);
        switch($this->getPropConfig($prop, 'type')){
            case 'data':
                if(isset($this->post_data[$key]) || $this->post_data[$key] !== $value){
                    $this->props_mutated[] = $key;
                }
                $this->post_data[$key] = $value;
                break;
            case 'meta':
                if(isset($this->post_meta[$key]) || $this->post_meta[$key] !== $value){
                    $this->props_mutated[] = $key;
                }
                $this->post_meta[$key] = $value;
                break;
        }
    }
    public function get($prop, $default=null){
        $key = $this->getPropConfig($prop, 'key', $prop);
        switch($this->getPropConfig($prop, 'type')){
            case 'data':
                $value = $this->post_data[$key];
                break;
            case 'meta':
                $value = $this->post_meta[$key];
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

    public function setProps($props=[]){
        foreach($props as $prop => $value){
            $this->set($prop, $value);
        }
    }

    public function getId(){
        return $this->id;
    }
    public function getPermalink(){
        return $this->id ? get_permalink($this->id) : false;
    }

    public function toArray(){
        $data = [
            'id' => $this->id,
            'permalink' => $this->getPermalink()
        ];
        foreach(array_keys($this->props_config) as $prop){
            $data[$prop] = $this->get($prop);
        }
        return $data;
    }
}