<?php

/**
 * Plugin Name:     Gucu
 * Plugin URI:      https://gucu.club/
 * Description:     Custom Queries for gucu.com
 * Author:          Younes DRO
 * Author URI:      https://github.com/younes-dro
 * Text Domain:     gucu
 * Domain Path:     /languages
 * Version:         4.2.0
 *
 * @package         Gucu
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * 
 */
if ( substr ( get_bloginfo('url'), 7 ,  3 ) !== '127' ){
    define( 'BIBLE_ID' , 337 );
    define( 'COMMENTARY_ID' , 536 );
}else{
    define( 'BIBLE_ID' , 218 );
    define( 'COMMENTARY_ID' , 225);    
}
/**
 * Gucu_Custom_Queries class.
 * 
 * The main instance of the plugin.
 * 
 * @since 1.0.0
 */
class Gucu_Custom_Queries{
    
    /** 
     * The Single instance of the class.
     * 
     * @var obj Gucu_Custom_Queries object
     */
    protected static $instance;
   
    /** 
     * Plugin Version.
     * 
     * @var String 
     */
    public $version = '1.0.0';
    
    /**
    * Plugin Name
    *
    * @var String 
    */
    public $plugin_name = 'Custom Queries for gucu.com';
    
    /** 
     * Instance of the Gucu_Custom_Queries_Dependencies class.
     * 
     * Verify the requirements.
     * 
     * @var obj Gucu_Custom_Queries_Dependencies object  
     */
    protected static $dependencies;
    
    /** @var array the admin notices to add */
    protected $notices = array();
    
    /**
     * Check the dependencies that the plugin needs.
     * 
     * @param obj dependencies
     */
    public function __construct( Gucu_Custom_Queries_Dependencies $dependencies) {
        
        self::$dependencies = $dependencies;
        
        register_activation_hook( __FILE__ , array( $this , 'activation_check' ) );
        
        add_action( 'admin_init', array( $this , 'check_environment' ) );
        
        add_action( 'admin_init', array( $this, 'add_plugin_notices') );
        
        add_action( 'admin_notices', array( $this, 'admin_notices' )  , 15 );
        
        add_action( 'plugins_loaded', array ( $this , 'init_plugin') );
        
        add_action( 'init' , array( $this , 'load_textdomain' ) );
               
          
    }    
    
    /**
     * Gets the main Gucu_Custom_Queries instance.
     * 
     * Ensures only one instance of Gucu_Custom_Queries is loaded or can be loaded.
     * 
     * @since 1.0.0
     * @param Obj $dependencies Check the dependencies that the plugin needs
     * 
     * @return Gucu_Custom_Queries instance
     */
    public static function start( Gucu_Custom_Queries_Dependencies $dependencies ){
        if ( NULL === self::$instance){
            self::$instance = new self( $dependencies );
        }
        
        return self::$instance;
    }
    
    /**
     * Cloning is forbidden due to singleton pattern.
     * 
     * @since 1.0.0
     */
    public function __clone() {
        $cloning_message = sprintf( 
                esc_html__( 'You cannot clone instances of %s.', 'gucu' ) ,
                get_class( $this )  
                );
        _doing_it_wrong( __FUNCTION__, $cloning_message, $this->version );
    }
    
    /**
     * Unserializing instances is forbidden due to singleton pattern.
     * 
     * @since 1.0.0
     */
    public function __wakeup() {
        $unserializing_message = sprintf( 
                esc_html__( 'You cannot clone instances of %s.', 'gucu' ) ,
                get_class( $this )  
                );
                _doing_it_wrong( __FUNCTION__, $unserializing_message, $this->version );
    }
    
    /**
     * Checks the server environment and deactivates plugins as necessary.
     * 
     * @since 1.0.0
     */
    public  function activation_check() {

        if ( ! self::$dependencies->check_php_version() ){
            
            $this->deactivate_plugin();
            
            wp_die( $this->plugin_name . esc_html__(' could not be activated. ', 'gucu' ) . self::$dependencies->get_php_notice() );
            
        }
    }
    
    /**
     * Checks the environment on loading WordPress, just in case the environment changes after activation.
     * 
     * @since 1.0.0
     */
    public function check_environment(){
        
        if ( ! self::$dependencies->check_php_version() && is_plugin_active( plugin_basename( __FILE__ ) ) ){
            
            $this->deactivate_plugin();
            $this->add_admin_notice( 
                    'bad_environment',
                    'error', 
                    $this->plugin_name . esc_html__( ' has been deactivated. ', 'gucu' ) . self::$dependencies->get_php_notice() 
                    );
        }
    }
    
    /**
     * Deactivate the plugin
     * 
     * @since 1.0.0
     */
    protected function deactivate_plugin(){
        
        deactivate_plugins( plugin_basename( __FILE__ ));
        
        if ( isset( $_GET['activate'] ) ){
            unset( $_GET['activate'] );
        }
    }
    
    /** 
    * Adds an admin notice to be displayed.
    *
    * @since 1.0.0
    *
    * @param string $slug message slug
    * @param string $class CSS classes
    * @param string $message notice message
    */
    public function add_admin_notice( $slug, $class, $message ) {

        $this->notices[ $slug ] = array(
			'class'   => $class,
			'message' => $message
		);
    } 
        
    public function add_plugin_notices() {
            
        if ( ! self::$dependencies->check_wp_version() ){
                
            $this->add_admin_notice( 'update_wordpress', 'error', self::$dependencies->get_wp_notice() );
        }
            
    }
        
    /** 
    * Displays any admin notices added with \Gucu_Custom_Queries::add_admin_notice()
    *
    * @since 1.0.0
    */
    public function admin_notices() {
        
        foreach ( (array) $this->notices as $notice_key => $notice ) {

            echo "<div class='" . esc_attr( $notice['class'] ) . "'><p>";
		echo wp_kses( $notice['message'], array( 
                            'a' => array( 
                                'href' => array() 
                                ),
                            'strong' => array() 
                            ));
			echo "</p></div>";
	}
    }        
    
    /**
    * Initializes the plugin.
    * 
    * @since 1.0.0
    */
    public function init_plugin() {
        
        if ( ! self::$dependencies->is_compatible() ){
            
            return;
                 
        }
        
        if( ! is_admin()  ){  
            add_action('pre_get_posts' , function( $query ){
                if ( $query->is_main_query()  && ( is_category('213') || is_category('216') ) ) {

                    $today = getdate();

                    $query->set('date_query', array(
                        array(
                            'day' => $today['mday'],
                            'month' => $today['mon']
                        ),
                    ));
                    $query->set('post_status', array('publish', 'future'));
                }

                return;
            });            
            
        }
        
        add_filter( 'theme_page_templates' , array( $this , 'load_custom_template') );
        add_filter('page_template', array($this ,  'catch_bible_template' ) );
        add_filter('page_template', array($this ,  'catch_commentary_template' ) );
        add_action( 'wp_enqueue_scripts', array( $this , 'gucu_enqueue') );
        
        add_action( 'wp_ajax_gucu_ajax_request', array ( $this , 'gucu_ajax_request' ) );
        add_action( 'wp_ajax_nopriv_gucu_ajax_request', array ( $this , 'gucu_ajax_request' ) );
        add_action( 'wp_ajax_grid_ajax_request', array ( $this , 'grid_ajax_request' ) );
        add_action( 'wp_ajax_nopriv_grid_ajax_request', array ( $this , 'grid_ajax_request' ) );
        add_action( 'wp_ajax_full_post_ajax_request', array ( $this , 'full_post_ajax_request' ) );         
        add_action( 'wp_ajax_nopriv_full_post_ajax_request', array ( $this , 'full_post_ajax_request' ) ); 
        
        if ( is_admin() ){
            
            add_action ( 'init' , array ( $this , 'add_custom_meta_box' ) );
        }
        
    }
    public function load_custom_template( $templates ){
        
        $templates['bible.php'] = 'Bible Page Template';
        $templates['commentary.php'] = 'Commentary Page Template';
        
        return $templates;
    }
    public function catch_bible_template( $template ){
        
        if( is_page_template('bible.php') ){
            
            $template = $this->plugin_path() . '/template/bible.php';
        }
        // Return
        return $template;        
    }
    
    public function catch_commentary_template( $template ){
        
        if( is_page_template('commentary.php') ){
            
            $template = $this->plugin_path() . '/template/commentary.php';
        }
        // Return
        return $template;        
    }  
    
    public function gucu_enqueue(){
        if( is_page_template( 'bible.php' ) || is_page_template( 'commentary.php' ) ){
            
            wp_enqueue_script( 'gucu-select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js', array( 'jquery' ), Gucu_Custom_Queries()->version, true );
            wp_enqueue_script('gucu-slick-js', $this->plugin_url() . '/assets/slick/slick.js', array('jquery',), Gucu_Custom_Queries()->version, true);            
            wp_enqueue_script( 'gucu-custom-js', $this->plugin_url(). '/assets/gucu-js.js' , array('jquery'), Gucu_Custom_Queries()->version, true);
            
            wp_enqueue_style( 'gucu-custom-css', $this->plugin_url() . '/assets/gucu-css.css' );
            wp_enqueue_style( 'gucu-selec2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css', array( ), Gucu_Custom_Queries()->version );
            
            wp_enqueue_style('gucu-slick-css', $this->plugin_url() . '/assets/slick/slick.css');
            wp_enqueue_style('gucu-slick-theme-css', $this->plugin_url() . '/assets/slick/slick-theme.css');        
            
            wp_localize_script(
		'gucu-custom-js',
		'gucu_ajax_obj',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce('ajax-nonce')
		)
            );            
                
        }
    }
    
    public function gucu_ajax_request (){
        
        Gucu_Ajax::sendRequest('');
    }
    
    public function grid_ajax_request (){
        
        Gucu_Ajax::sendRequest('grid');
    }    
    public function full_post_ajax_request(){
        Gucu_Ajax::sendRequest('fullpost');
    }
    public function add_custom_meta_box (){
        Gucu_Admin::Attach_Category();
    }

    /*-----------------------------------------------------------------------------------*/
    /*  Helper Functions                                                                 */
    /*-----------------------------------------------------------------------------------*/
        
    /**
    * Get the plugin url.
    * 
    * @since 1.0.0
    * 
    * @return string
    */
    public function plugin_url(){
        
        return untrailingslashit( plugins_url( '/', __FILE__ ) );
    
    }
        
    /**
    * Get the plugin path.
    * 
    * @since 1.0.0
    * 
    * @return string
    */
    public function plugin_path(){
        
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    
    }
        
    /**
    * Get the plugin base path name.
    * 
    * @since 1.0.0
    * 
    * @return string
    */
    public function plugin_basename(){
        
        return plugin_basename( __FILE__ );
        
    }
    
    /**
     * Register the built-in autoloader
     * 
     * @codeCoverageIgnore
     */
    public static function register_autoloader ( ){
        spl_autoload_register( array ( 'Gucu_Custom_Queries' , 'autoloader' ) );
    }
    
    /**
     * Register autoloader.
     * 
     * @param string $class Class name to load
     */
    public static function autoloader ( $class_name ){
        
        $class = strtolower ( str_replace( '_', '-' , $class_name ) );
        $file  = plugin_dir_path ( __FILE__ ) . '/includes/class-' . $class . '.php'; 
        if ( file_exists( $file ) ){
            require_once $file;
        }
    }
    
    public function load_textdomain(){
        load_plugin_textdomain( 'gucu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }
}

/**
 * Returns the main instance of Gucu_Custom_Queries.
 */
function Gucu_Custom_Queries(){
    
    Gucu_Custom_Queries::register_autoloader();
    return Gucu_Custom_Queries::start( new Gucu_Custom_Queries_Dependencies() );
    
}

Gucu_Custom_Queries();



