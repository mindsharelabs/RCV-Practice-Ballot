<?php
/**
 * Plugin Name: Ranked Choice Voting Practice Ballot
 * Plugin URI: https://mind.sh/are
 * Description: A Plugin to add a Mock Practice Ballot to any WordPress site
 * Version: 1.0.0
 * Author: Mindshare Labs, Inc
 * Author URI: https://mind.sh/are
 */


class rcvBallotPlugin {
  private $options = '';

  private $token = '';

  protected static $instance = NULL;

  public function __construct() {
    if ( !defined( 'RCV_BALLOTPLUGIN_FILE' ) ) {
    	define( 'RCV_BALLOTPLUGIN_FILE', __FILE__ );
    }
    //Define all the constants
    $this->define( 'RCV_BALLOTABSPATH', dirname( RCV_BALLOTPLUGIN_FILE ) . '/' );
    $this->define( 'RCV_BALLOTPLUGIN_VERSION', '1.4.6');
    $this->define( 'RCV_BALLOTPREPEND', 'rcv_ballot_' );
    $this->define( 'RCV_BALLOTURL', plugin_dir_url( __FILE__ ));

    $this->includes();
    $this->theme_support();

    add_action( 'admin_enqueue_scripts', array($this, 'enque_admin_scripts_and_styles'), 100 );
    add_action( 'wp_enqueue_scripts', array($this, 'enque_front_scripts_and_styles'), 100 );


    $this->options = get_option( 'rcv_ballot_support_settings' );
    $this->token = (isset($this->options['rcv_ballot_api_token']) ? $this->options['rcv_ballot_api_token'] : false);


	}
  public static function get_instance() {
    if ( null === self::$instance ) {
  		self::$instance = new self;
  	}
  	return self::$instance;
  }
  private function define( $name, $value ) {
    if ( ! defined( $name ) ) {
      define( $name, $value );
    }
  }

  public function theme_support() {
    add_image_size('candidate-showcase', 250, 250, array('center', 'center'));
    add_image_size( 'ballot-thumbnail', 80, 80, array('center', 'center'));
  }

  private function includes() {
    //General
    include_once RCV_BALLOTABSPATH . 'inc/utility.php';
    include_once RCV_BALLOTABSPATH . 'inc/options.php';
    include_once RCV_BALLOTABSPATH . 'inc/ajax.class.php';
    include_once RCV_BALLOTABSPATH . 'inc/blocks.class.php';
    include_once RCV_BALLOTABSPATH . 'inc/cpt.php';
    include_once RCV_BALLOTABSPATH . 'inc/front.class.php';
  }

  public function enque_front_scripts_and_styles() {

    wp_register_script('rcv-js', plugins_url('js/main.js', RCV_BALLOTPLUGIN_FILE), array('jquery'), RCV_BALLOTPLUGIN_VERSION, true);

    wp_localize_script( 'rcv-js', 'mindeventsSettings', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'post_id' => get_the_id(),
      'practice_ballot_args' => array(
        'post_id' => get_the_id(),
        'fields' => get_fields(get_the_id())
      ),
    ));
  }


  public function enque_admin_scripts_and_styles() {


	}









}//end of class


new rcvBallotPlugin();
