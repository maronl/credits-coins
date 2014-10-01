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

        cc_set_validation_rules_post_types_values();
        if( ! jQuery('form[action="options.php"]').valid() ) {
            return;
        }

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

    jQuery.validator.addMethod("valueNotEquals", function(value, element, arg){
        return arg != value;
    }, "Value must not equal arg.");

    jQuery('#submit').click(function(e){
        cc_set_validation_rules_forms();
    });

    cc_set_validation_rules_forms();

});

var cc_set_validation_rules_forms = function() {
    jQuery('form[action="options.php"]').removeData('validator');
    jQuery('form[action="options.php"]').validate({
        rules: {
            "credits-coins-options[new-user-default-credits]": {
                required: true,
                digits: true,
                min: 1
            }
        },

        messages: {
            "credits-coins-options[new-user-default-credits]": "Enter a number greater than 0",
        }
    });
}

var cc_set_validation_rules_post_types_values = function() {
    jQuery('form[action="options.php"]').removeData('validator');
    jQuery('form[action="options.php"]').validate({
        rules: {
            "new-post-type-value-name": {
                required: true,
                valueNotEquals: 0
            },
            "new-post-type-value-credit":{
                required: true,
                digits: true,
                min: 1
            }
        },

        messages: {
            "new-post-type-value-name": "Select a post type",
            "new-post-type-value-credit": "Enter a number greater than 0"
        }
    });

}