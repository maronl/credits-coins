<div class="jumbotron">
    <?php wp_create_nonce( "my-special-string" ); ?>
    <h1>To see the full article or issue you have to buy it!</h1>
    <p><a id="btn-buy-post" href="#<?php echo get_the_ID(); ?>" class="btn btn-primary btn-lg" role="button">Buy now</a></p>
</div>

