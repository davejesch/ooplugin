<?php

class SlugPublic
{
	private static $_instance = NULL;

	private $plugin = NULL;

	private function __construct($plugin)
	{
		$this->plugin = $plugin;

		add_action('wp_register_scripts', array(&$this, 'register_scripts'));

		add_shortcode('plugin_shortcode', array(&$this, 'shortcode'));
	}

	/**
	 * Returns the singleton instance for this class
	 * @param Object $plugin The parent plugin's instance
	 * @return Object The single instance to the SlugPublic class
	 */
	public static function get_instance($plugin)
	{
		if (NULL === self::$_instance)
			self::$_instance = new self($plugin);
		return (self::$_instance);
	}

	/**
	 * Callback for the 'wp_register_scripts' action
	 */
	public function register_scripts()
	{
		// register all styles and scripts used by the plugin; but only enqueue what's needed

		wp_register_style(SlugPlugin::PLUGIN_SLUG,				// handle of style
			$this->plugin->get_assets_url('css/slugplugin.css'),	// url to location of asset
			array(),											// dependencies
			SlugPlugin::PLUGIN_VERSION,							// version
			'all');												// media

		wp_enqueue_script(SlugPlugin::PLUGIN_SLUG,				// handle of script
			$this->plugin->get_assets_url('js/slugplugin.js'),	// URL to location of script
			array('jquery'),									// list of dependencies
			SlugPlugin::PLUGIN_VERSION,							// version
			TRUE);												// in footer
	}

	/**
	 * Shortcode callback
	 * @param array $atts Attributes for the shortcode being activated
	 * @param string $content The contents of the shortcode being activated
	 */
	public function shortcode($atts = array(), $content = '')
	{
		// now we know the script is needed, so enqueue it
		wp_enqueue_script(SlugPlugin::PLUGIN_SLUG);
	}
}

// EOF