<div class="wrap">
    <h1 class="wp-heading-inline">Hello WooP, <?= WOOPN_VERSION;?></h1>
    <form action='options.php' method='post'>

			<h2>wooplugin</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
</div>