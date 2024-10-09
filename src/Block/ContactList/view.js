import './view.scss';

jQuery(function($){

    function fetchList(itemsCont){
        $.get(`${sgcVars.apiBase}sgc/v1/block/contact-list/find`)
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
        const itemsCont = block.find(".items-cont");
        
        if(!itemsCont.length){
            return;
        }

        fetchList(itemsCont);
    });
});