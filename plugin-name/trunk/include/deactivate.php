<?php

/**
 * Uninstallation code for the Sample Plugin
 *
 * @link       http://SpectrOMtech.com
 * @since      1.0.0
 *
 * @package    SlugPlugin
 */
class SlugDeactivate
{
	/**
	 * 
	 */
	public static function uninstall()
	{
		/*
		* - Check if the $_REQUEST content actually is the plugin name
		* - Run an admin referrer check to make sure it goes through authentication
		* - Verify the output of $_GET makes sense
		* - Repeat with other user roles. Best directly by using the links/query string parameters.
		* - Repeat things for multisite. Once for a single site in the network, once sitewide.
		*/
	}
}

!defined('WP_UNINSTALL_PLUGIN') || die;

// EOF