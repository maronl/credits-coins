jQuery(function() {
    jQuery('#btn-buy-post').click(function(event){
        ajaxurl = ajax_credits_coins.url;
        event.preventDefault();
        if(!jQuery(this).hasClass('button-disabled')){
            jQuery(this).addClass('button-disabled');
            jQuery('.alert').fadeOut(function(){jQuery(this).remove();});
            jQuery.post(
                ajaxurl,
                {
                    'action':'buy_post',
                    'post_id':jQuery(this).attr('href').slice(1),
                    'security': ajax_credits_coins.security,
                },
                function(response){
                    jQuery('#btn-buy-post').removeClass('button-disabled');
                    if(response.status == 1){
                        jQuery('#btn-buy-post').after(jQuery(credits_coins_render_alert_buy_post_success(response.msg)).hide());
                        jQuery('.alert').fadeIn();
                        setTimeout(function(){location.reload();}, 3000);
                    }else if(response.status == 0){
                        jQuery('#btn-buy-post').after(jQuery(credits_coins_render_alert_buy_post_error(response.msg)).hide());
                        jQuery('.alert').fadeIn();
                    }
                },
                'json'
            );
        }
    });

});

function credits_coins_render_alert_buy_post_success( message ) {
    html = '<div role="alert" class="alert alert-success">' +
        message +
        '</div>';
    return html;
}

function credits_coins_render_alert_buy_post_error( message ) {
    html = '<div role="alert" class="alert alert-danger">' +
        message +
        '</div>';
    return html;
}