<?php 
namespace SGC\Block\Contact;

class Block extends \SGC\Block\Base {

    private $route;

    public function __construct(){
        parent::__construct('Contact', [
            'render_callback' => [$this, 'render']
        ]);

        $this->route = new \SGC\Route\Base('sgc/v1/block/contact');

        $this->route->addRoute('/retrieve-countries', [
            'methods' => 'GET',
            'callback' => [$this, 'retrieveCountries']
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

        $recaptcha_key = '6LcgdVwqAAAAAB7VfixakTREkze985G9tBZtSZmh';
        $recaptcha_id = uniqid('sgc_recaptcha_');
        $has_recaptcha = (!$post_id && $recaptcha_key);

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
                        <div class="form-floating control-cont mb-3">
                            <div class="control-spinner spinner-border spinner-border-sm" role="status"></div>
                            <select name="customer_country" class="form-control mb-3" aria-label="<?php _e('Country', 'sgc'); ?>" data-value="<?php echo $type_contact->get('customer_country'); ?>">
                                <option value=""><?php _e('-- Select country', 'sgc'); ?></option>
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
                    <div class="row">
                        <div class="col-md-6">
                            <?php if($has_recaptcha && $recaptcha_key): ?>
                            <div class="mb-3">
                                <div id="<?php echo $recaptcha_id; ?>" class="sgc-recaptcha" data-sitekey="<?php echo $recaptcha_key; ?>"></div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 text-end">
                                <button type="submit" class="btn btn-dark"><?php _e('Submit', 'sgc'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="status-messages"></div>
            </form>
        </div>

        <?php 
        return parent::endRender();
    }

    public function retrieveCountries($req){

        try {
            $countries = \SGC\Service\Countries::retrieveList();

            $this->route->setResponseData($countries);
            $this->route->addResponseMeta('total', count($countries));

        } catch(\Exception $e) {
            $this->route->addResponseError($e->getMessage(), $e->getCode());
        }

        return $this->route->getResponse();
    }
}
