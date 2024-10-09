import './view.scss';

jQuery(function($){

    function submitForm(url, postId, form){
        const formData = new FormData(form.get(0));
        $.ajax(url, {
            method: postId ? "PUT" : "POST",
            // data: form.serialize(),
            data: {
                id: postId,
                data: (()=> {
                    let values = {};
                    for(const [key, value] of formData.entries()){
                        values[key] = value;
                    }
                    return values;
                })()
            },
            dataType: "json"
        })
            .done((resp) => {
                if(!postId){
                    form.get(0).reset();
                }
            })
            .fail((err) => {
                console.log(err);
            });
    }

    $(".sgc-block--contact").each(function(){

        const block = $(this);
        const apiBaseUrl = block.data("api_base_url");
        const postId = +block.data("post_id");
        const form = block.find("form");
        // const statusCont = block.find("status-cont");

        form.on("submit", function(e){
            e.preventDefault();
            submitForm(
                postId ? `${apiBaseUrl}sgc/v1/contact/update` : `${apiBaseUrl}sgc/v1/contact/insert`, 
                postId,
                form
            );
        });
    });
});