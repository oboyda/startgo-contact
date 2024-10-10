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

    protected function renderInputAtts($name, $type_object){

        $atts = [];
        $config = $type_object->getPropConfig($name);
        
        if(isset($config['maxlength'])){
            $atts['maxlength'] = $config['maxlength'];
        }
        if(isset($config['required']) && $config['required']){
            $atts['required'] = 'required';
        }

        $atts_items = [];
        foreach($atts as $key => $value){
            $atts_items[] = $key.'="'.$value.'"';
        }
        $atts_str = $atts_items ? ' '.implode(' ', $atts_items) : '';

        return $atts_str;
    }

    protected function printNotAllowedContent(){
        ?>
        <p class="my-5 text-center"><?php _e('You are not allowed to see this content.', 'sgc'); ?></p>
        <?php 
    }
}
