<div class="wrap">
    <h2>Instillinger for online timebestilling</h2>
    <?php settings_errors(); ?>
	<form action="options.php" method="post">
		<?php settings_fields('opus-settings'); ?>
		<?php do_settings_sections('opus-settings'); ?>
		<?php submit_button(); ?>
	</form>
</div>