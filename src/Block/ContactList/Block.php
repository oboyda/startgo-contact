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

        <div class="<?php echo "sgc-block--list color-{$atts['color']}" ?>" data-api_base_url="<?php echo get_rest_url(); ?>">

            <h3 class="block-title mb-3"><?php _e('Contacts List', 'sgc'); ?></h3>

            <div class="items-cont"></div>
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
                // return (new \SGC\Type\Contact($post))->toArray();
                return [
                    'id' => $type_contact->getId(),
                    'html' => '<div class="contact-item">'.$type_contact->get('title').'</div>'
                ];
            }, $posts_query->posts));

            $this->route->addResponseMeta('total', $posts_query->found_posts);

        } catch(\Exception $e) {
            $this->route->addResponseError($e->getMessage(), $e->getCode());
        }

        return $this->route->getResponse();
    }
}
