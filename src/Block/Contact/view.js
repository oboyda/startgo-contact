import './view.scss';

let recaptchaWidgets = {};
window.sgcRecaptchaCallback = function () {
    const widgets = document.body.querySelectorAll(".sgc-recaptcha");
    for(const widget of widgets){
        recaptchaWidgets[widget.id] = grecaptcha.render(widget);
    }
};

jQuery(function ($) {

    function displayFormStatusMessage(form, message, type='success') {
        const statusCont = form.find(".status-messages");
        statusCont.html(`<p class="type-${type}">${message}</p>`);
        setTimeout(() => {
            statusCont.html("");
        }, 5000);
    }

    function submitForm(url, postId, form) {

        const formData = new FormData(form.get(0));
        const formDataValues = (() => {
            let values = {};
            for (const [key, value] of formData.entries()) {
                if (['recaptcha_token'].includes(key)) continue;
                values[key] = value;
            }
            return values;
        })();

        const recaptchaId = form.find(".sgc-recaptcha").prop("id");
        const regaptchaWidgetId = (recaptchaId && typeof recaptchaWidgets[recaptchaId] !== 'undefined') ? recaptchaWidgets[recaptchaId] : null;
        const regaptchaToken = (regaptchaWidgetId !== null && typeof grecaptcha !== 'undefined') ? grecaptcha.getResponse(regaptchaWidgetId) : null;

        $.ajax(url, {
            method: postId ? "PUT" : "POST",
            // data: form.serialize(),
            data: {
                id: postId,
                data: formDataValues,
                recaptcha_token: regaptchaToken
            },
            dataType: "json"
        })
            .done((resp) => {
                if(!postId){
                    form.get(0).reset();
                }
                if(!!resp.meta?.message){
                    displayFormStatusMessage(form, resp.meta.message, 'success');
                }
            })
            .fail((err) => {
                if(!!err?.responseJSON?.message){
                    displayFormStatusMessage(form, err.responseJSON.message, 'error');
                }
            })
            .always((resp) => {
                if(regaptchaWidgetId !== null && typeof grecaptcha !== 'undefined'){
                    grecaptcha.reset(regaptchaWidgetId);
                }
            });
    }

    function populateCustomerCountryInput(url, form){
        const contriesInput = form.find("select[name='customer_country']");
        if(!contriesInput.length){
            return;
        }
        const dataValue = contriesInput.data("value");
        contriesInput.closest(".control-cont").addClass("is-loading");
        contriesInput.addClass("is-loading");
        $.get(url)
            .done((resp) => {
                if(!!resp.data?.length){
                    const options = resp.data.map((item) => {
                        const selected = (item.code == dataValue) ? " selected" : "";
                        return `<option value="${item.code}"${selected}>${item.name}</option>`;
                    });
                    if(options.length){
                        contriesInput.append(options.join(""));
                    }
                }
            })
            .always(() => {
                contriesInput.closest(".control-cont").removeClass("is-loading");
                contriesInput.removeClass("is-loading");
            });
    }

    $(".sgc-block--contact").each(function () {

        const block = $(this);
        const apiBaseUrl = block.data("api_base_url");
        const postId = +block.data("post_id");
        const form = block.find("form");

        populateCustomerCountryInput(`${apiBaseUrl}sgc/v1/block/contact/retrieve-countries`, form);

        form.on("submit", function (e) {
            e.preventDefault();
            submitForm(
                postId ? `${apiBaseUrl}sgc/v1/contact/update` : `${apiBaseUrl}sgc/v1/contact/insert`,
                postId,
                form
            );
        });
    });
});