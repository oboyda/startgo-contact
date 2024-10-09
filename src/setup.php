<?php 

function sgc_load_textdomain(){
    load_plugin_textdomain('sgc', false, basename(SGC_ROOT) . '/languages');
}
add_action('init', 'sgc_load_textdomain');

function sgc_add_polylang_switch_shortcode($atts){
    ob_start();
    if(function_exists('pll_the_languages')){
        // echo '<ul>';
            pll_the_languages([
                'dropdown' => 1
            ]);
        // echo '</ul>';
    }
    $html = ob_get_contents();
    ob_end_clean();
	return $html;
}
add_shortcode('sgc-polylang-switch', 'sgc_add_polylang_switch_shortcode');
