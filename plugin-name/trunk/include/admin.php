<?php

/**
 * Administration code for the Sample Plugin
 *
 * @link       http://SpectrOMtech.com
 * @since      1.0.0
 *
 * @package    SlugPlugin
 */
class SlugAdmin
{
	private static $_instance = NULL;

	private $_plugin = NULL;					// reference to main plugin instance
	private $_options = NULL;					// reference to options array
	private $_settings = NULL;					// the SpectrOMSettings instance

	private function __construct($plugin)
	{
		$this->_plugin = $plugin;
		add_action('admin_enqueue_scripts', array(&$this, 'register_scripts'));

		add_action('admin_init', array(&$this, 'admin_init'));
		add_action('admin_menu', array(&$this, 'admin_menu'));
	}

	/**
	 * Return a Singleton instance of the plugin
	 * @return object Returns the instance of the plugin
	 */
	public static function get_instance($plugin)
	{
		if (NULL === self::$_instance)
			self::$_instance = new self($plugin);
		return (self::$_instance);
	}

	/*
	 * Retreve options for this site from the database
	 */
	public function get_options()
	{
		$this->_options = get_option(SlugPlugin::OPTION_NAME);
	}
	public function get_option($name, $default = NULL)
	{
		if (isset($this->_options[$name]))
			return ($this->_options[$name]);
		return ($default);
	}

	/**
	 * Callback for 'admin_init' action
	 */
	public function admin_init()
	{
		// do admin side initialization here
	}

	/**
	 * Sets up the admin menu
	 */
	public function admin_menu()
	{
		$this->_plugin->load_class('class.spectromvalidation.php');
		$this->_plugin->load_class('class.spectromsettings.php');

		$this->_create_settings_object();

		add_options_page(SlugPlugin::PLUGIN_NAME . 'Settings',				// page title
			SlugPlugin::PLUGIN_NAME . ' Settings',							// menu title
			'manage_options',												// capability
			$this->_settings->get_page(),									// menu slug
			array(&$this, 'settings_page'));								// callback function
	}

	/*
	 * Creates an instance of the SpectrOMSettings object from an array of data
	 */
	private function _create_settings_object()
	{
		if (NULL !== $this->_settings)
			return;
		$this->get_options();

		$args = array(
			'page' => 'ooplugin_settings',
			'group' => 'ooplugin_settings', // _group
			'option' => SlugPlugin::OPTION_NAME,
			'sections' => array(
				'ooplugin_settings' => array(
					'title' => __('My Plugin Settings', 'ooplugin'),
					'description' => __('This is an explanation of the Settings Section', 'ooplugin'),
					'fields' => array(
						'text_setting' => array(
							'id' => 'text_setting',
							'title' => __('Enter Text Here', 'ooplugin'),
							'type' => 'text',
							'class' => 'test-class',
							'validation' => 'maxlen:30',
							'value' => $this->get_option('text_setting', 'default first name')
						),
						'email_setting' => array(
							'title' => __('Enter your email address:', 'ooplugin'),
							'tooltip' => __('Enter email address to send emails to', 'ooplugin'),
							'type' => 'text',
							'validation' => 'required email minlen:5 maxlen:64',
							'value' => $this->get_option('email_setting'),
						),
						'dropdown_setting' => array(
							'title' => __('Select from the list:', 'ooplugin'),
							'type' => 'select',
							'options' => array('1' => 'value 1', '2' => 'value 2', '3' => 'value three'),
							'option-title' => '- select value -',
							'class' => 'select-class',
							'value' => $this->get_option('dropdown_setting', '2'),
						),
						'radio_setting' => array(
							'title' => __('Pick one:', 'ooplugin'),
							'type' => 'radio',
							'options' => array('N' => 'North', 'S' => 'South', 'E' => 'East', 'W' => 'West'),
							'class' => 'radio-class',
							'value' => $this->get_option('radio_setting', 'S'),
						),
						'checkbox_setting' => array(
							'title' => __('Optionally Check this:', 'ooplugin'),
							'afterinput' => 'is checked',
							'type' => 'checkbox',
							'value' => $this->get_option('checkbox_setting', 1),
							'description' => __('Use this to make a checkbox', 'ooplugin'),
						),
						'textarea_setting' => array(
							'title' => __('Enter a paragraph of text:', 'ooplugin'),
							'type' => 'textarea',
							'class' => 'ootextarea',
							'validation' => 'maxlen:200 striphtml',
							'size' => array(35, 10),
							'value' => $this->get_option('textarea_setting', 'default text'),
						),
						'ip_setting' => array(
							'title' => __('Enter your IP address:', 'ooplugin'),
							'type' => 'text',
							'class' => 'ootext',
							'validation' => 'required regex:/(\d+).(\d+).(\d+).(\d+)$/',
							'error' => __('Please enter a valid IP address', 'ooplugin'),
							'value' => $this->get_option('ip_setting', ''),
							'description' => __('Enter an IP address that will be validated by regex', 'ooplugin'),
						),
						'button_setting' => array(
							'title' => __('Click the button:', 'ooplugin'),
							'type' => 'button',
							'class' => 'button-primary',
							'value' => __('Click Me', 'ooplugin'),
						),
					),
				),
			),
		);

		$this->_settings = new SpectrOMSettings($args);
	}

	/**
	 * Output the settings page
	 */
	public function settings_page()
	{
		$this->_create_settings_object();

		echo '<div class="wrap">', PHP_EOL;
		echo	'<h2>', $this->_settings->get_header(), '</h2>', PHP_EOL;
		echo	'<form action="options.php" method="post">', PHP_EOL;

		// use the settings object to output sections and fields
		$this->_settings->settings_fields();
		$this->_settings->settings_sections();
		submit_button();

        echo	'</form>', PHP_EOL;
		echo '</div>', PHP_EOL;
	}

	/**
	 * Loads a view file
	 * @param string $name The name of the view file, without the .php extension
	 * @param array $data An array of data values to be passed to the view file
	 * @param boolean $ret TRUE if the contents of the view file should be returned; FALSE if the contents should be echoed.
	 * @return mixed The string containing the processed view contents or FALSE if the $ret parameter was FALSE.
	 */
	public function load_view($name, $data = NULL, $ret = FALSE)
	{
		$plugin_view_file = $this->_plugin->get_directory('views') . 'admin/' . $name . '.php';
		if (NULL !== $data)
			extract($data);

		if ($ret)
			ob_start();
		include($plugin_view_file);
		if ($ret) {
			$res = ob_get_clean();
			return ($res);
		}
	}

	/**
	 * Registers the scripts and styles used by the admin code
	 */
	public function register_scripts()
	{
		// register all the scripts, but don't enqueue them unless you need them
		wp_register_style(SlugPlugin::PLUGIN_SLUG,						// handle to identify the style
			$this->_plugin->get_assets_url('css/slugpluginadmin.css'),		// location of asset
			array(),													// dependencies
			SlugPlugin::PLUGIN_VERSION,									// version
			'all');														// media

		wp_register_script(SlugPlugin::PLUGIN_SLUG,						// handle to identify the script
			$this->_plugin->get_assets_url('js/slugpluginadmin.js'),		// location of asset
			array('jquery'),											// dependencies
			SlugPlugin::PLUGIN_VERSION,									// version
			TRUE);														// in footer
	}
}

// EOF