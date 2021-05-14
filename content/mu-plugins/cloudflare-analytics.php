<?php

add_action( 'wp_footer', function() {
        ?>
        <script defer src='https://static.cloudflareinsights.com/beacon.min.js' data-cf-beacon='{"token": "bacf7bdee81f44d4a585fffadf80e4e0"}'></script>
        <?php
} );
