<?php 
namespace SGC\Block;

class Base {

    private $block_name;
    private $block_args;

    public function __construct($name, $args=[]){
        $this->block_name = $name;
        $this->block_args = $args;

        add_action('init', [$this, 'registerBlock']);
    }

    public function registerBlock(){
        register_block_type(SGC_ROOT . '/build/Block/' . $this->block_name, $this->block_args);
    }

    protected function startRender(){
        ob_start();
    }
    protected function endRender(){
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
}
