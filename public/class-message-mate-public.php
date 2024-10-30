<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ownerlistens.com
 * @since      1.0.0
 *
 * @package    Message_Mate
 * @subpackage Message_Mate/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Message_Mate
 * @subpackage Message_Mate/public
 * @author     OwnerListens <support@ownerlistens.com>
 */
class Message_Mate_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Message_Mate_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Message_Mate_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/message-mate-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Message_Mate_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Message_Mate_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/message-mate-public.js', array( 'jquery' ), $this->version, false );

	}

	public function insert_mm_script() {
	  $options = get_option($this->plugin_name);
	  if(isset($options['mm_id'])){
        $mm_id =  trim($options['mm_id']);
        if(strlen($mm_id)){
            //more mm_id validations here?
             wp_enqueue_script( 'ownerlistens-mm-script', '//ownerlistens.com/static/js/ol_sms_box.js?mm_id=' . $mm_id . "&v=1.5.8.1" );
        }

	  }
    }


    public function add_defer_tag( $tag, $handle ) {
            if ( 'ownerlistens-mm-script' !== $handle )
                return $tag;
            return str_replace( ' src', ' defer async src', $tag );
    }


}
