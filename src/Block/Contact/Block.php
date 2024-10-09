<?php 
namespace SGC\Block\Contact;

class Block extends \SGC\Block\Base {

    public function __construct(){
        parent::__construct('Contact', [
            'render_callback' => [$this, 'render']
        ]);
    }

    public function render($atts){
        global $post;

        $post_id = (isset($post) && $post->post_type == 'sgc_contact') ? $post->ID : 0;
        $type_contact = new \SGC\Type\Contact($post_id);

        /*
        Populate some user data if logged in
        */
        if(!$post_id && is_user_logged_in()){
            $current_user = wp_get_current_user();
            $current_user_meta = [
                'first_name' => get_user_meta($current_user->ID, 'first_name', true),
                'last_name' => get_user_meta($current_user->ID, 'last_name', true)
            ];
            if($current_user_meta['first_name']){
                $type_contact->set('customer_first_name', $current_user_meta['first_name']);
            }
            if($current_user_meta['last_name']){
                $type_contact->set('customer_last_name', $current_user_meta['last_name']);
            }
            if($current_user?->data?->user_email){
                $type_contact->set('customer_email', $current_user->data->user_email);
            }
        }

        $atts = array_merge([
            'color' => 'white'
        ], $atts);

        parent::startRender();
        ?>

        <div class="<?php echo "sgc-block--contact color-{$atts['color']}" ?>" data-api_base_url="<?php echo get_rest_url(); ?>" data-post_id="<?php echo $post_id; ?>">
            <h3 class="block-title mb-3"><?php _e('Contact', 'sgc'); ?></h3>
            <form class="sgc-block--contact-form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input name="customer_first_name" type="text" class="form-control" placeholder="<?php _e('First name', 'sgc'); ?>" aria-label="<?php _e('First name', 'sgc'); ?>" value="<?php echo $type_contact->get('customer_first_name'); ?>" />
                            <label><?php _e('First name', 'sgc'); ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input name="customer_last_name" type="text" class="form-control mb-3" placeholder="<?php _e('Last name', 'sgc'); ?>" aria-label="<?php _e('Last name', 'sgc'); ?>" value="<?php echo $type_contact->get('customer_last_name'); ?>" />
                            <label><?php _e('Last name', 'sgc'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input name="customer_email" type="email" class="form-control mb-3" placeholder="<?php _e('Email', 'sgc'); ?>" aria-label="<?php _e('Email', 'sgc'); ?>" value="<?php echo $type_contact->get('customer_email'); ?>" />
                            <label><?php _e('Email', 'sgc'); ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select name="customer_country" class="form-control mb-3" aria-label="<?php _e('Country', 'sgc'); ?>">
                                <option value="">--</option>
                            </select>
                            <label><?php _e('Country', 'sgc'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating mb-3">
                        <textarea name="comments" class="form-control control--comments" placeholder="<?php _e('Comments', 'sgc'); ?>"><?php echo $type_contact->get('comments'); ?></textarea>
                        <label><?php _e('Comments', 'sgc'); ?></label>
                    </div>
                </div>
                <div class="mb-3 mt-4">
                    <button type="submit" class="btn btn-dark"><?php _e('Submit', 'sgc'); ?></button>
                </div>
                <div class="status-cont"></div>
            </form>
        </div>

        <?php 
        return parent::endRender();
    }
}
