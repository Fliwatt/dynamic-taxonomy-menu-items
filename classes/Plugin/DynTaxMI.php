<?php
/**
 *  Main plugin class file.
 *
 * @package DynTaxMI
 * @subpackage Plugin_Core
 * @since 20170111
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2017, Richard Coffee
 * @link https://github.com/RichardCoffee/custom-post-type/blob/master/classes/Plugin/Base.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  Dynamic Taxonomy Menu Items.
 *
 * @since 20180404
 * @link https://github.com/RichardCoffee/dynamic-taxonomy-menu-items/blob/master/classes/Plugin/DynTaxMI.php
 */
class DynTaxMI_Plugin_DynTaxMI extends DynTaxMI_Plugin_Plugin {


	/**
	 *
	 * @since 20200410
	 * @var string  Path to plugin options page, used on the WP Dashboard Plugins page
	 */
	protected $setting = 'themes.php?page=dyntaxmi';


	/**
	 * @since 20200201
	 * @link https://github.com/RichardCoffee/custom-post-type/blob/master/classes/Trait/Singleton.php
	 */
	use DynTaxMI_Trait_Singleton;


	/**
	 *  Initialize the plugin.
	 *
	 * @since 20180404
	 */
	public function initialize() {
		if ( ( ! DynTaxMI_Register_Plugin::php_version_check() ) || ( ! DynTaxMI_Register_Plugin::wp_version_check() ) ) {
			return;
		}
		register_deactivation_hook( $this->paths->file, [ 'DynTaxMI_Register_Plugin', 'deactivate' ] );
		register_uninstall_hook(    $this->paths->file, [ 'DynTaxMI_Register_Plugin', 'uninstall'  ] );
		$this->add_actions();
		$this->add_filters();
		if ( is_admin() ) {
			new DynTaxMI_Form_DynTaxMI;
		}
	}

	/**
	 *  Establish actions.
	 *
	 * @since 20180404
	 */
	public function add_actions() {
		add_action( 'wp_head',            [ $this, 'wp_head' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
		parent::add_actions();
	}

	/**
	 *  Establish filters.
	 *
	 * @since 20180404
	 */
	public function add_filters() {
		parent::add_filters();
	}

	/**
	 *  Run during 'wp_head' action.
	 *
	 * @since 20200407
	 */
	public function wp_head() {
		$this->add_taxonomy();
		$this->add_custom_css();
	}

	/**
	 *  Add a taxonomy to the menu.
	 *
	 * @since 20200406
	 */
	protected function add_taxonomy() {
		$options = get_option( 'tcc_options_dyntaxmi', array() );
		$defaults = $this->get_taxonomy_defaults();
		$taxonomy = array_merge( $defaults, $options );
		dyntaxmi_tax( $taxonomy );
	}

	/**
	 *  Provides taxonomy default values.
	 *
	 * @since 20200411
	 * @return array
	 */
	protected function get_taxonomy_defaults() {
		return array(
			'css_action' => 'dyntaxmi_custom_css',
			'count'      => true,
			'exclude'    => [ 1 ], //  Uncategorized
			'limit'      => 0,
			'maximum'    => 7,
			'menu'       => 'primary-menu',
			'position'   => 1,
			'title'      => __( 'Articles', 'dyntaxmi' ),
			'type'       => 'category',
		);
	}

	/**
	 *  Add custom css for taxonomy menu items.
	 *
	 * @since 20200407
	 */
	protected function add_custom_css() {
		$attrs = array(
			'id'   => 'dyntaxmi-custom-css',
			'type' => 'text/css',
		);
		dyntaxmi()->tag( 'style', $attrs );
		do_action( 'dyntaxmi_custom_css' );
		echo '</style>';
	}

	/**
	 *  Load style sheet.
	 *
	 * @since 20200407
	 */
	public function wp_enqueue_scripts() {
		wp_enqueue_style( 'dyntaxmi-css', $this->paths->get_plugin_file_uri( 'css/dyntaxmi.css' ), null, $this->paths->version );
	}


}
