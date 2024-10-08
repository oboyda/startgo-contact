import './view.scss';

jQuery(function($){

    function submitForm(url, form){
        $.ajax(url, {
            method: "POST",
            // data: form.serialize(),
            data: {
                data: (()=> {
                    let values = {};
                    for(const [key, value] of (new FormData(form.get(0))).entries()){
                        values[key] = value;
                    }
                    return values;
                })()
            },
            dataType: "json"
        })
            .done((resp) => {
                console.log(resp);
                form.get(0).reset();
            })
            .fail((err) => {
                console.log(err);
            });
    }

    $(".sgc-block--contact").each(function(){

        const block = $(this);
        const apiBaseUrl = block.data("api_base_url");
        const form = block.find("form");
        const statusCont = block.find("status-cont");

        form.on("submit", function(e){
            e.preventDefault();
            submitForm(`${apiBaseUrl}sgc/v1/contact/insert`, form);
        });
    });
});