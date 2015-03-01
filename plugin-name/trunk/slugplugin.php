<?php
/*
Plugin Name: Slug Plugin
Plugin URI: http://SpectrOMtech.com
Description: This is a sample Object Oriented Plugin Skeleton
Version: 1.0.0
Author: Dave Jesch
Author URI: http://SpectrOMtech.com
License: GPL 2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: slugplugin
Domain Path: /language
*/

/**
 * Sample Object Oriented plugin
 * @link	http://SpectrOMtech.com
 * @since	1.0.0
 * @package	SlugPlugin
*/

class SlugPlugin
{
	private static $_instance = NULL;

	const PLUGIN_NAME = 'Sample Plugin';	// plugin's full name
	const PLUGIN_VERSION = '1.0';				// plugin version
	const PLUGIN_SLUG = 'slugplugin';				// plugin slug name
	const PLUGIN_DOMAIN = 'slugplugin';			// the text domain used by the plugin
	const OPTION_NAME = 'slugplugin_options';

	private $dir_plugin = NULL;			// the directory where the plugin code is installed
	private $dir_include = NULL;		// the directory where the plugin include files are located
	private $dir_assets = NULL;			// the directory where the plugin assets are located
	private $url_assets = NULL;			// the URL to the plugin assets directory

	private function __construct()
	{
		$this->dir_plugin = dirname(__FILE__) . DIRECTORY_SEPARATOR;
		$this->dir_include = $this->dir_plugin . 'include' . DIRECTORY_SEPARATOR;
		$this->dir_assets = $this->dir_plugin . 'assets' . DIRECTORY_SEPARATOR;
		$this->url_assets = plugin_dir_url(__FILE__) . 'assets/';

		register_activation_hook(__FILE__, array(&$this, 'activate'));
		register_deactivation_hook(__FILE__, array(&$this, 'deactivate'));

		if (is_admin()) {
			$this->load('admin.php');
			SlugAdmin::get_instance($this);
		} else {
			$this->load('public.php');
			SlugPublic::get_instance($this);
		}

		add_action('plugins_loaded', array(&$this, 'load_textdomain'));
	}

	/**
	 * Return a Singleton instance of the plugin
	 * @return object Returns the instance of the plugin
	 */
	public static function get_instance()
	{
		if (NULL === self::$_instance)
			self::$_instance = new self();
		return (self::$_instance);
	}

	/**
	 * Plugin activation callback. Called once when the plugin is activated.
	 */
	public function activate()
	{
		$this->load('activate.php');
		SlugActivate::install();
	}

	/**
	 * Plugin deactivation callback. Called once when the plugin is deactivated.
	 */
	public function deactivate()
	{
		$this->load('deactivate.php');
		SlugDeactivate::uninstall();
	}

	/**
	 * Returns one of the plugin's directories.
	 * @param string $dir The plugin subdirectory name
	 * @return string The directory with a trailing slash
	 */
	public function get_directory($dir = NULL)
	{
		$dir = $this->dir_plugin . (NULL === $dir ? '' : $dir . DIRECTORY_SEPARATOR);
		return ($dir);
	}

	/**
	 * Returns a URL to the plugin's assets directory
	 * @param string $asset The directory name nad file name of the asset
	 * @return string The URL referencing the plugin's asset
	 */
	public function get_assets_url($asset = NULL)
	{
		$url = $this->url_assets;
		if (NULL !== $asset)
			$url .= $asset;
		return ($url);
	}

	/**
	 * Loads the plugin's textdomain
	 */
	public function load_textdomain()
	{
		load_plugin_textdomain(
			'slugplugin',						// the text domain (see Plugin Headers)
			FALSE,								// deprecated parameter
			$this->get_directory('language'));
	}

	/**
	 * Loads a specific class name
	 * @param string $file The name of the class file to load
	 */
	public function load_class($file)
	{
		$this->load('classes' . DIRECTORY_SEPARATOR . $file);
	}
	public function load($file)
	{
		include_once($this->dir_include . $file);
	}

	/**
	 * Performs logging
	 * @global type $wp_current_filter
	 * @param string $msg A message to be logged
	 */
	public static function log($msg = NULL)
	{
//return;
		$file = dirname(__FILE__) . '/~log.txt';
		$fh = fopen($file, 'a+');
		if (FALSE !== $fh) {
			if (NULL === $msg)
				fwrite($fh, date("\r\nY-m-d H:I:s:\r\n"));
			else {
				global $wp_current_filter;
				$act = '';
				if (($cnt = count($wp_current_filter)) > 0)
					$act = '[' . $wp_current_filter[$cnt - 1] . '] ';

				fwrite($fh, date('Y-m-d H:i:s| ') . "{$msg}    {$act}\r\n");
			}
			fclose($fh);
		}
	}
}

if (!defined('ABSPATH')) { header('Status: 403 Forbidden');  header('HTTP/1.1 403 Forbidden'); die('Forbidden'); }
SlugPlugin::get_instance();

// EOF