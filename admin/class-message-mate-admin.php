<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ownerlistens.com
 * @since      1.0.0
 *
 * @package    Message_Mate
 * @subpackage Message_Mate/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Message_Mate
 * @subpackage Message_Mate/admin
 * @author     OwnerListens <support@ownerlistens.com>
 */
class Message_Mate_Admin {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/message-mate-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/message-mate-admin.js', array( 'jquery' ), $this->version, false );

	}


    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    public function add_plugin_admin_menu() {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        add_options_page( 'Message Mate Options', 'Message Mate', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
        );
    }

     /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */

    public function add_action_links( $links ) {
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
       $settings_link = array(
        '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
       );
       return array_merge(  $settings_link, $links );

    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */


    private function get_by_curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,  $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }



    private function get_data( $mm_id){
            if (!isset($mm_id) || !$mm_id){
                $mm_id = "empty"; //just for debug/errors tracking
            }

            try {
                $site_url = urlencode ( get_bloginfo('url') );
            } catch (Exception $e) {
                $site_url = "unknown";
            }

            $host = "https://ownerlistens.com";
            $path = '/message_mate/settings/?platform=wordpress&domain=' . $site_url . '&mm_id=' . $mm_id . '&plugin=1.4';
            $url = $host.$path;


            if( get_cfg_var('allow_url_fopen')){
                $data = file_get_contents( $url."&f=fget");
            }
            else if (function_exists('curl_init')){
                $data = $this->get_by_curl($url."&f=curl");
            }
            else{
                $fp = fsockopen("ssl://ownerlistens.com", 443, $errno, $errstr, 30);
                $resp = "";
                if (!$fp) {
                    echo "$errstr ($errno)<br />\n";
                } else {
                    $path = $path."&f=fsock";
                    $out = "GET ".$path."  HTTP/1.1\r\n";
                    $out .= "Host: ownerlistens.com\r\n";
                    $out .= "Connection: Close\r\n\r\n";
                    fwrite($fp, $out);
                    while (!feof($fp)) {
                        $resp.=fgets($fp, 128);
                    }
                    fclose($fp);
                }
                list($header, $data) = preg_split("/\R\R/", $resp, 2);
            }

            $arr = json_decode($data, true);
            if(array_key_exists('settings',$arr) &&  is_array($arr['settings'])){
                    return $arr;
            }
            return false;
    }


    public function display_plugin_setup_page() {
        include_once( 'partials/message-mate-admin-display.php' );
    }


    public function validate($input) {
        $valid = array();
        $message = null;
        $type = null;
        $has_errors = true;

        $mm_id =  isset($input['mm_id']) ? $input['mm_id']: "";

        $arr = $this->get_data($mm_id);
        if(is_array($arr)){
           $has_errors = false;
        }


        if (!$has_errors) {

            $valid['mm_id'] = $mm_id;
            $type = 'updated';
            $message = __( 'Token successfully saved.', 'message-mate' );


        }
        else{
            $valid['mm_id'] = "";
            $type = 'error';
            $message = __( 'Invalid Token: Are you sure you copied it correctly?', 'message-mate' );
        }




        add_settings_error(
            'mm_id',
            esc_attr( 'settings_updated' ),
            $message,
            $type
        );
        return $valid;
     }



    public function options_update() {
            register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
     }







}
