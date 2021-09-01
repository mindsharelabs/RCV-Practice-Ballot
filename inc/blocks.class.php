<?php
/**
 *
 */
class rcvShortcodes {
  function __construct() {
    //Add Our Shorcodes
    add_shortcode( 'rcv_ballot_ballot', array($this, 'ballot'));

    add_filter('block_categories_all', array($this, 'add_block_categories') , 10, 2);
    add_action('acf/init', array($this, 'register_blocks') );
    add_action('acf/init', array($this, 'register_fields') );
  }

  public function ballot() {
    wp_enqueue_script('rcv-js');
    wp_enqueue_style('rcv-css');

    echo '<div class="container" id="practiceBallot"></div>';
  }


  /**
  * Register custom Gutenberg blocks category
  *
  */
  public function add_block_categories ($categories, $post) {
  	return array_merge(
  		$categories,
  		array(
  			array(
  				'slug' 	=> 'rcv-rcv-blocks',
  				'title' => __('RCV RCV Blocks', 'mindshare'),
  				'icon' 	=> file_get_contents(RCV_BALLOTABSPATH . '/img/rcv-rcv-icon.svg'),
  			),

  		)
  	);
  }



  public function register_blocks () {


    // Check function exists.
    if( function_exists('acf_register_block_type') ) {
      acf_register_block_type(array(
        'name'              => 'ballot-block',
        'title'             => __('Practice Ballot'),
        'description'       => __('The Ranked Choice Voting Practice Ballot.'),
        'render_template'   => RCV_BALLOTABSPATH . '/inc/block-templates/ballot-block.php',
        'category'          => 'rcv-rcv-blocks',
        'icon'              => file_get_contents(RCV_BALLOTABSPATH . 'img/circle-checked.svg'),
        'keywords'          => array( 'RCV', 'RCV', 'Ballot', 'Practice', 'Vote', 'Mindshare' ),
        'align'             => 'full',
        'mode'            	=> 'preview',
        'multiple'          => false,
        'supports'					=> array(
          'align' => false,
        ),
        'enqueue_assets' => function(){
          // We're just registering it here and then with the action get_footer we'll enqueue it.
          wp_register_style( 'styles', RCV_BALLOTURL . 'inc/css/style.css' );
          add_action( 'get_footer', function () {
            wp_enqueue_style('styles');
          });
          wp_enqueue_script('rcv-js');

          },
        )
      );


    }
  }


  public function register_fields() {
    if( function_exists('acf_add_local_field_group') ):

      acf_add_local_field_group(array(
      	'key' => 'group_610d82b2436c3',
      	'title' => 'CPT: Candidates',
      	'fields' => array(
      		array(
      			'key' => 'field_611123a87c445',
      			'label' => 'Candidate Options',
      			'name' => 'candidate_options',
      			'type' => 'group',
      			'instructions' => '',
      			'required' => 0,
      			'conditional_logic' => 0,
      			'wrapper' => array(
      				'width' => '',
      				'class' => '',
      				'id' => '',
      			),
      			'layout' => 'block',
      			'sub_fields' => array(
      				array(
      					'key' => 'field_610d82b7f4af9',
      					'label' => 'Candidate Color',
      					'name' => 'candidate_color',
      					'type' => 'color_picker',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '',
      						'class' => '',
      						'id' => '',
      					),
      					'default_value' => '',
      				),
      				array(
      					'key' => 'field_610d82d55af1d',
      					'label' => 'Candidate Icon Image',
      					'name' => 'candidate_icon_image',
      					'type' => 'image',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '',
      						'class' => '',
      						'id' => '',
      					),
      					'return_format' => 'array',
      					'preview_size' => 'medium',
      					'library' => 'all',
      					'min_width' => '',
      					'min_height' => '',
      					'min_size' => '',
      					'max_width' => '',
      					'max_height' => '',
      					'max_size' => '',
      					'mime_types' => '',
      				),
      				array(
      					'key' => 'field_61116d570cb1b',
      					'label' => 'Candidate Slogan',
      					'name' => 'candidate_slogan',
      					'type' => 'text',
      					'instructions' => '',
      					'required' => 0,
      					'conditional_logic' => 0,
      					'wrapper' => array(
      						'width' => '',
      						'class' => '',
      						'id' => '',
      					),
      					'default_value' => '',
      					'placeholder' => '',
      					'prepend' => '',
      					'append' => '',
      					'maxlength' => '',
      				),
      			),
      		),
      		
      	),
      	'location' => array(
      		array(
      			array(
      				'param' => 'post_type',
      				'operator' => '==',
      				'value' => 'rcv_candidates',
      			),
      		),
      	),
      	'menu_order' => 0,
      	'position' => 'normal',
      	'style' => 'default',
      	'label_placement' => 'top',
      	'instruction_placement' => 'label',
      	'hide_on_screen' => '',
      	'active' => true,
      	'description' => '',
      ));

    endif;

  }




}

new rcvShortcodes;
