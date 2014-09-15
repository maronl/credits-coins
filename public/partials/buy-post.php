<div class="jumbotron">
    <?php wp_create_nonce( "my-special-string" ); ?>
    <h1>To see the full article or issue you have to buy it!</h1>
    <p><a id="btn-buy-post" href="#<?php echo get_the_ID(); ?>" class="btn btn-primary btn-lg" role="button">Buy now</a></p>

    <!--
    <div role="alert" class="alert alert-danger fade in" style="display: none">
        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4>Oh snap! You got an error!</h4>
        <p>Change this and that and try again. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
        <p>
            <button class="btn btn-danger" type="button">Take this action</button>
            <button class="btn btn-default" type="button">Or do this</button>
        </p>
    </div>
    -->

</div>

