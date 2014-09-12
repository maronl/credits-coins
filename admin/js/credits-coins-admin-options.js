jQuery(function() {
    jQuery( 'ul.post-types-values-list' ).on( 'click', 'li a.remove-post-type-value', function() {
        remove_data = jQuery( this).attr('href').slice(1);
        old_settings = jQuery( '#post-types-values').val();
        old_settings = old_settings.split(';');
        check_setting = jQuery.inArray(remove_data, old_settings);
        if( check_setting >= 0 ){
            old_settings.splice(check_setting, 1);
            new_settings = old_settings.join(';');
            jQuery( 'li.post-types-values-item[data-value="'+remove_data+'"]' ).remove();
            jQuery( '#post-types-values' ).val(new_settings);
            remove_data = remove_data.split(',');
            jQuery('#new-post-type-value-name option[value="' + remove_data[0] + '"]').removeAttr('disabled');
        }
        if( jQuery( 'ul.post-types-values-list li').length == 1 ){
            jQuery( '#no-post-types-values-message').removeClass( 'hidden' );
        }
    });

    jQuery( '#add-post-type-value' ).on( 'click', function() {
        if(post_type = jQuery( "#new-post-type-value-name option:selected" ).val() == 0)
            return;

        post_type = jQuery( "#new-post-type-value-name option:selected" ).val();
        post_type_value = jQuery( "#new-post-type-value-credit" ).val();
        old_settings = jQuery( '#post-types-values' ).val();
        new_setting = post_type + ',' + post_type_value;
        if(old_settings == ''){
            old_settings = new Array();
        }else{
            old_settings = old_settings.split(';');
        }
        check_setting = old_settings.indexOf(new_setting);
        if(check_setting < 0){

            jQuery( "#new-post-type-value-name option:selected" ).attr('disabled','disabled');
            jQuery( "#new-post-type-value-name" ).val(0);

            old_settings.push(new_setting);

            new_setting_html = '<li '
                + 'data-value="' + new_setting + '" class="post-types-values-item">'
                + post_type + '=&gt; ' + post_type_value + ' Credits - '
                + '<a href="#' + new_setting + '" class="remove-post-type-value">remove</a>'
                + '</li>';
            jQuery( 'ul.post-types-values-list').append( new_setting_html );
            if( jQuery( 'ul.post-types-values-list li').length == 1 ){
                jQuery( '#no-post-types-values-message').removeClass( 'hidden' );
            }
        }
        new_settings = old_settings.join(';');
        jQuery( '#post-types-values' ).val(new_settings);
    });

    jQuery( 'ul.credits-by-group-list' ).on( 'click', 'li a.remove-credits-by-group-value', function() {
        remove_data = jQuery( this).attr('href').slice(1);
        old_settings = jQuery( '#credits-by-group-values').val();
        old_settings = old_settings.split(';');
        check_setting = jQuery.inArray(remove_data, old_settings);
        if( check_setting >= 0 ){
            old_settings.splice(check_setting, 1);
            new_settings = old_settings.join(';');
            jQuery( 'li.credits-by-group-item[data-value="'+remove_data+'"]' ).remove();
            jQuery( '#credits-by-group-values' ).val(new_settings);
            remove_data = remove_data.split(',');
        }
        if( jQuery( 'ul.credits-by-group-list li').length == 1 ){
            jQuery( '#no-credits-by-group-values-message').removeClass( 'hidden' );
        }
    });

    jQuery( '#add-credits-by-group-value' ).on( 'click', function() {
        credits_number = jQuery( "#new-credits-by-group-value" ).val();
        credits_price = jQuery( "#new-credits-by-group-price" ).val();

        if(credits_number == 0 || credits_price == 0)
            return;

        old_settings = jQuery( '#credits-by-group-values' ).val();
        new_setting = credits_number + ',' + credits_price;
        if(old_settings == ''){
            old_settings = new Array();
        }else{
            old_settings = old_settings.split(';');
        }
        check_setting = old_settings.indexOf(new_setting);
        if(check_setting < 0){

            old_settings.push(new_setting);

            new_setting_html = '<li '
                + 'data-value="' + new_setting + '" class="credits-by-group-item">'
                + credits_number + ' Credits =&gt; ' + credits_price + ' Euro - '
                + '<a href="#' + new_setting + '" class="remove-credits-by-group-value">remove</a>'
                + '</li>';
            jQuery( 'ul.credits-by-group-list').append( new_setting_html );
            if( jQuery( 'ul.credits-by-group-list li').length == 1 ){
                jQuery( '#no-credits-by-group-values-message').removeClass( 'hidden' );
            }
        }
        new_settings = old_settings.join(';');
        jQuery( '#credits-by-group-values' ).val(new_settings);
    });


});