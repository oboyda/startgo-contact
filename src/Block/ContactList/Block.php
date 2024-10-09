<?php 
namespace SGC\Block\ContactList;

class Block extends \SGC\Block\Base {

    private $route;

    public function __construct(){
        parent::__construct('ContactList', [
            'render_callback' => [$this, 'render']
        ]);

        $this->route = new \SGC\Route\Base('sgc/v1/block/contact');

        $this->route->addRoute('/list', [
            'methods' => 'GET',
            'callback' => [$this, 'list']
        ]);
    }

    public function render($atts){

        $atts = array_merge([
        ], $atts);

        parent::startRender();
        ?>

        <section class="<?php echo "sgc-block--list color-{$atts['color']}" ?>" data-api_base_url="<?php echo get_rest_url(); ?>">
            <div class="container-fluid">
                <h3 class="block-title mb-3"><?php _e('Contacts List', 'sgc'); ?></h3>
                <div class="items-cont"></div>
            </div>
        </section>

        <?php 
        return parent::endRender();
    }

    public function renderItem($data){

        parent::startRender();
        ?>

        <div class="<?php echo "list-item id-{$data['id']}" ?>">
            <div class="row">
                <div class="col-sm-7">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="item-meta">
                                <div class="meta-label"><?php _e('First Name', 'sgc'); ?></div>
                                <div class="meta-value"><?php echo $data['customer_first_name']; ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="item-meta">
                                <div class="meta-label"><?php _e('Last Name', 'sgc'); ?></div>
                                <div class="meta-value"><?php echo $data['customer_last_name']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="item-meta">
                        <div class="meta-label"><?php _e('Email', 'sgc'); ?></div>
                        <div class="meta-value"><?php echo $data['customer_email']; ?></div>
                    </div>
                </div>
            </div>
            <div class="item-actions py-1 text-end">
                <button type="button" class="btn btn-secondary btn-sm"><?php _e('View more', 'sgc'); ?></button>
                <a href="<?php echo $data['permalink']; ?>" target="_blank" class="btn btn-primary btn-sm"><?php _e('Edit', 'sgc'); ?></a>
            </div>
        </div>

        <?php 
        return parent::endRender();
    }

    public function list($req){

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

            $this->route->setResponseData(array_map(function($post){
                $type_contact = new \SGC\Type\Contact($post);
                return [
                    'id' => $type_contact->getId(),
                    'html' => $this->renderItem($type_contact->toArray())
                ];
            }, $posts_query->posts));

            $this->route->addResponseMeta('total', $posts_query->found_posts);

        } catch(\Exception $e) {
            $this->route->addResponseError($e->getMessage(), $e->getCode());
        }

        return $this->route->getResponse();
    }
}
