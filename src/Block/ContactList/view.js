import './view.scss';

jQuery(function($){

    function fetchList(url, itemsCont){
        $.get(url)
            .done(function(resp){
                if(!!resp?.data?.length){
                    resp.data.forEach((item) => {
                        if(item?.html){
                            itemsCont.append(item.html);
                        }
                    });
                }
            });
    }

    $(".sgc-block--list").each(function(){

        const block = $(this);
        const apiBaseUrl = block.data("api_base_url");
        const itemsCont = block.find(".items-cont");

        fetchList(`${apiBaseUrl}sgc/v1/block/contact-list/find`, itemsCont);
    });
});