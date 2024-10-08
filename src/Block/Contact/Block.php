<?php 
namespace SGC\Block\Contact;

class Block extends \SGC\Block\Base {

    public function __construct(){
        parent::__construct('Contact', [
            'render_callback' => [$this, 'render']
        ]);
    }

    public function render($atts){

        $atts = array_merge([
            'color' => 'white'
        ], $atts);

        parent::startRender();
        ?>

        <div class="<?php echo "sgc-block--contact color-{$atts['color']}" ?>">

            <h3 class="block-title mb-3"><?php _e('Contact', 'sgc'); ?></h3>

            <form class="sgc-block--contact-form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" placeholder="<?php _e('First name', 'sgc'); ?>" aria-label="<?php _e('First name', 'sgc'); ?>">
                            <label><?php _e('First name', 'sgc'); ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control mb-3" placeholder="<?php _e('Last name', 'sgc'); ?>" aria-label="<?php _e('Last name', 'sgc'); ?>">
                            <label><?php _e('Last name', 'sgc'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control mb-3" placeholder="<?php _e('Email', 'sgc'); ?>" aria-label="<?php _e('Email', 'sgc'); ?>">
                            <label><?php _e('Email', 'sgc'); ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select class="form-control mb-3" aria-label="<?php _e('Country', 'sgc'); ?>">
                                <option value="">--</option>
                            </select>
                            <label><?php _e('Country', 'sgc'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating mb-3">
                        <textarea class="form-control control--comments" placeholder="<?php _e('Comments', 'sgc'); ?>"></textarea>
                        <label><?php _e('Comments', 'sgc'); ?></label>
                    </div>
                </div>
                <div class="mb-3 mt-4">
                    <button type="submit" class="btn btn-dark" disabled><?php _e('Submit', 'sgc'); ?></button>
                </div>
                <div class=""></div>
            </form>
        </div>

        <?php 
        return parent::endRender();
    }
}
