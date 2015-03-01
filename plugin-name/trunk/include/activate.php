<?php

/**
 * Installation code for the Sample Plugin
 */
class SlugActivate
{
	/**
	 * Performs the installaction process for the plugin
	 */
	public static function install()
	{
		// perform installation steps
		self::create_tables();
	}

	/**
	 * Creates any database tables needed by the plugin.
	 * @global type $wpdb
	 */
	private static function create_tables()
	{
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$charset_collate = '';
		if (!empty($wpdb->charset))
			$charset_collate = " DEFAULT CHARACTER SET $wpdb->charset";
		if (!empty($wpdb->collate))
			$charset_collate .= " COLLATE $wpdb->collate";

		// optional additional prefixing- created tables like wp_slug_tablename
		$prefix = SlugPlugin::PLUGIN_SLUG . '_';

		ob_start();						// output buffering hides any errors generated during table alters
		$sql = self::get_table_data();
		$sql = str_replace('CREATE TABLE `', 'CREATE TABLE `' . $wpdb->prefix . $prefix, $sql) . $charset_collate;
SlugPlugin::log(__METHOD__.'() executing: ' . $sql);
		$ret = dbDelta($sql);
SlugPlugin::log('  result=' . var_export($ret, TRUE));
		$output = ob_get_clean();
	}

	/**
	 * Returns the table declaration SQL statement
	 * @return string The SQL instructions to create the table
	 */
	private static function get_table_data()
	{
		$sql = <<<EOS
				CREATE TABLE `plugindata` (
					`pd_id`			INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`pd_post_id`	BIGINT(20) UNSIGNED NOT NULL,
					`pd_timestamp` 	TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

					PRIMARY KEY (`pd_id`),
					INDEX `post_id` (`pd_post_id`)
				) ENGINE=InnoDB
EOS;
		return ($sql);
	}
}

// EOF