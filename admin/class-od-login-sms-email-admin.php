<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://olivas.digital
 * @since      1.0.0
 *
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Od_Login_Sms_Email
 * @subpackage Od_Login_Sms_Email/admin
 * @author     Olivas Digital <contato@olivasdigital.com.br>
 */
class Od_Login_Sms_Email_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/od-login-sms-email-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/od-login-sms-email-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Create a config page for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function config_page() {

		$page = 'od-login-sms-email/admin/pages/od-login-sms-email-configs.php';

		add_menu_page(
	        __( 'SMS/Email Login', 'od-login-sms-email' ),
	        __( 'SMS/Email Login', 'od-login-sms-email' ),
	        'manage_options',
	        $page,
	        '',
	        'dashicons-admin-comments',
	        81
	    );

		add_submenu_page(
			$page,
	        __( 'SMS/Email Login - Settings', 'od-login-sms-email' ),
	        __( 'Settings', 'od-login-sms-email' ),
	        'manage_options',
	        get_admin_url() . '/admin.php?page=' . $page,
	        '',
	        1
	    );

	}

	/**
	 * Register configs.
	 *
	 * @since    1.0.0
	 */
	public function register_configs() {

		register_setting( 'od-login-sms-email-configs', 'od_login_sms_email_account' );
		register_setting( 'od-login-sms-email-configs', 'od_login_sms_email_password' );
		register_setting( 'od-login-sms-email-configs', 'od_login_sms_email_sms' );
		register_setting( 'od-login-sms-email-configs', 'od_login_sms_email_email' );

	}

	/**
	 * Register post type "Access Code".
	 *
	 * @since    1.0.0
	 */
	public function register_access_code() {

		$labels = array(
			'name'                  => _x( 'Access Codes', 'Post Type General Name', 'od-login-sms-email' ),
			'singular_name'         => _x( 'Access Code', 'Post Type Singular Name', 'od-login-sms-email' ),
			'menu_name'             => __( 'Access Codes', 'od-login-sms-email' ),
			'name_admin_bar'        => __( 'Access Code', 'od-login-sms-email' ),
			'archives'              => __( 'Archives', 'od-login-sms-email' ),
			'attributes'            => __( 'Attributes', 'od-login-sms-email' ),
			'parent_item_colon'     => __( 'Parent:', 'od-login-sms-email' ),
			'all_items'             => __( 'Access Codes', 'od-login-sms-email' ),
			'add_new_item'          => __( 'Add New', 'od-login-sms-email' ),
			'add_new'               => __( 'Add New', 'od-login-sms-email' ),
			'new_item'              => __( 'New', 'od-login-sms-email' ),
			'edit_item'             => __( 'Edit', 'od-login-sms-email' ),
			'update_item'           => __( 'Update', 'od-login-sms-email' ),
			'view_item'             => __( 'View', 'od-login-sms-email' ),
			'view_items'            => __( 'View', 'od-login-sms-email' ),
			'search_items'          => __( 'Search', 'od-login-sms-email' ),
			'not_found'             => __( 'Not found', 'od-login-sms-email' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'od-login-sms-email' ),
			'featured_image'        => __( 'Featured Image', 'od-login-sms-email' ),
			'set_featured_image'    => __( 'Set featured image', 'od-login-sms-email' ),
			'remove_featured_image' => __( 'Remove featured image', 'od-login-sms-email' ),
			'use_featured_image'    => __( 'Use as featured image', 'od-login-sms-email' ),
			'insert_into_item'      => __( 'Insert into', 'od-login-sms-email' ),
			'uploaded_to_this_item' => __( 'Uploaded to this', 'od-login-sms-email' ),
			'items_list'            => __( 'List', 'od-login-sms-email' ),
			'items_list_navigation' => __( 'List navigation', 'od-login-sms-email' ),
			'filter_items_list'     => __( 'Filter list', 'od-login-sms-email' ),
		);

		$args = array(
			'label'                 => __( 'Access Code', 'od-login-sms-email' ),
			'description'           => __( 'Login Access Codes', 'od-login-sms-email' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => 'od-login-sms-email/admin/pages/od-login-sms-email-configs.php',
			'menu_position'         => 80,
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'capability_type'       => 'page',
			'show_in_rest'          => false,
		);

		register_post_type( 'access_code', $args );

	}

	/**
	 * Register meta box.
	 */
	public function register_meta_box() {

	    add_meta_box( 
	    	'access_code_options', 
	    	__( 'Options', 'od-login-sms-email' ), 
	    	array( $this, 'display_meta_box' ), 
	    	'access_code', 
	    	'side', 
	    	'high' 
	    );
	}

	/**
	 * Meta box display callback.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function display_meta_box( $post ) {

	    include plugin_dir_path( __FILE__ ) . '/forms/od-login-sms-email-pt-options.php';
	}

	/**
	 * Save meta box content.
	 *
	 * @param int $post_id Post ID
	 */
	function save_meta_box( $post_id ) {

	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	    if ( $parent_id = wp_is_post_revision( $post_id ) ) {

	        $post_id = $parent_id;
	    }

	    $fields = [
	        'access_code',
	        'requester',
	        'exp_date',
	    ];

	    foreach ( $fields as $field ) {

	        if ( array_key_exists( $field, $_POST ) ) {

	            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
	        }
     	}
	}

}
