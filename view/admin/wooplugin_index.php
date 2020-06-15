<div class="wrap">
    <h1 class="wp-heading-inline">Hello from WooP, <?= WOOPN_VERSION;?></h1>
    <form action='options.php' method='post'>
			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
</div>