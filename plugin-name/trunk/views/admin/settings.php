<div class="wrap slugplugin">
	<h2>Settings for the Object Oriented Plugin</h2>

	<form action="options.php" method="POST">
		<?php
echo '<!-- calling settings_fields() -->', PHP_EOL;
		settings_fields($this->page);
echo '<!-- calling do_settings_sections() -->', PHP_EOL;
		do_settings_sections($this->section);
		submit_button();
		?>
	</form>
</div>
