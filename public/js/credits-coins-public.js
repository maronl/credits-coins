jQuery(function() {
    jQuery('#btn-buy-post').click(function(event){
        ajaxurl = ajax_credits_coins.url;
        event.preventDefault();
        if(!jQuery(this).hasClass('button-disabled')){
            jQuery(this).addClass('button-disabled');
            jQuery.post(
                ajaxurl,
                {
                    'action':'buy_post',
                    'post_id':jQuery(this).attr('href').slice(1),
                    'security': ajax_credits_coins.security,
                },
                function(response){
                    console.log(response);
                    jQuery('#btn-buy-post').removeClass('button-disabled');
                    if(response == 1){
                        location.reload();
                    }
                },
                'json'
            );
        }
    });

});