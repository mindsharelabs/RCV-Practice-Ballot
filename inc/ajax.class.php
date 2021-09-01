<?php


class rcvAjax {
  private $options = '';
  private $token = '';

  function __construct() {

    $this->options = get_option( 'mindevents_support_settings' );
    $this->token = (isset($this->options['mindevents_api_token']) ? $this->options['mindevents_api_token'] : false);

    add_action( 'wp_ajax_nopriv_' . RCV_BALLOTPREPEND . 'load_practice_ballot', array( $this, 'load_practice_ballot' ) );
    add_action( 'wp_ajax_' . RCV_BALLOTPREPEND . 'load_practice_ballot', array( $this, 'load_practice_ballot' ) );

    add_action( 'wp_ajax_nopriv_' . RCV_BALLOTPREPEND . 'submit_ballot', array( $this, 'submit_ballot' ) );
    add_action( 'wp_ajax_' . RCV_BALLOTPREPEND . 'submit_ballot', array( $this, 'submit_ballot' ) );


  }
  private function define( $name, $value ) {
    if ( ! defined( $name ) ) {
      define( $name, $value );
    }
  }

  public static function get_instance() {
    if ( null === self::$instance ) {
  		self::$instance = new self;
  	}
  	return self::$instance;
  }

  static function load_practice_ballot() {
    if($_POST['action'] == RCV_BALLOTPREPEND . 'load_practice_ballot'){
      $ballot = new rcvBallot();
      $html = $ballot->show_ballot();
      $return = array(
        'html' => $html
      );
      wp_send_json_success($return);
    }
  }
  static function get_candidates() {
    return get_posts(array(
      'post_type' => 'rcv_candidates',
      'posts_per_page' => -1
    ));
  }

  static function skipped_candidates($casted_votes) {
    $rcvAjax = new rcvAJAX();
    $candidates = $rcvAjax->get_candidates();
    $missing_votes = array();
    foreach ($casted_votes as $key => $vote) :
      $voted_for[] = $vote['candidate'];
    endforeach;
    foreach ($candidates as $key => $candidate) :
      if(!in_array($candidate->ID, $voted_for)) :
        $missing_votes[] = $candidate->ID;
      endif;
    endforeach;
    return $missing_votes;
  }

  static function duplicate_rankings($casted_votes) {
    $rcvAjax = new rcvAJAX();
    $ranks = array();
    $duplicates = array();



    return $duplicates;
  }

  static function submit_ballot() {
    if($_POST['action'] == RCV_BALLOTPREPEND . 'submit_ballot') {
      $casted_votes = (isset($_POST['casted_votes']) ? $_POST['casted_votes'] : false);

      $rcvAjax = new rcvAJAX();
      if($casted_votes) {

        $messages = array();
        $return = '';
        //Check for skipped candidates
        $skipped_candidates = $rcvAjax->skipped_candidates($casted_votes);



        //Check for Duplicate Rankings (Candidates Ranked the Same)
        $duplicate_rankings = array();
        $m_ranks = array();

        foreach ($casted_votes as $key => $vote) :
          $m_ranks[$vote['rank']][] = $vote['candidate'];
        endforeach;

        foreach ($m_ranks as $rank => $candidates) :
          if(count($candidates) > 1) :
            $duplicate_rankings[] = $rank;
          endif;
        endforeach;



        //Check for Multiple Rankings (Candidates Ranked Multiuple Times)
        $multuple_rankings = array();
        $m_candidate = array();

        foreach ($casted_votes as $key => $vote) :
          $m_candidate[$vote['candidate']][] = $vote['rank'];
        endforeach;

        foreach ($m_candidate as $candidate_id => $ranks) :
          if(count($ranks) > 1) :
            $multuple_rankings[] = $candidate_id;
          endif;
        endforeach;



        if(count($skipped_candidates) > 0) :
          $text = '';
          foreach ($skipped_candidates as $key => $candidate_id) :
            $text .= '<li>' . get_the_title( $candidate_id ) . '</li>';
          endforeach;
          $messages['warnings'][] = array(
            'text' => 'We encourage voters to rank all candidates. You skipped ranking the following candidates: <ul>' . $text . '</ul>'
          );
        endif;


        if(count($duplicate_rankings) > 0) {
          $text = '';
          foreach($duplicate_rankings as $key => $ranking) :
            switch($ranking) {
  						case 1:
  						  $rank_text = $ranking . '<sup>st</sup>';
  						  break;
  						case 2:
  							$rank_text = $ranking . '<sup>nd</sup>';
  							break;
  						case 3:
  							$rank_text = $ranking . '<sup>rd</sup>';
  							break;
  						default:
  							$rank_text = $ranking . '<sup>th</sup>';
  							break;
  					}

            if($key == 0) :
              $text .= $rank_text;
            elseif ($key+1 == count($duplicate_rankings)) :
              $text .= ' and ' . $rank_text;
            else :
              $text .= ', ' . $rank_text;
            endif;
          endforeach;
          $messages['error'][] = array(
            'text' => 'Oops. There are multiple rankings for your ' . $text . ' choices. When the ballot counter reads multiple candidates ranked the same, you will have to correct your ballot.'
          );
        }


        if(count($multuple_rankings) > 0) :
          $text = '';
          for ($c = 0; $c < count($multuple_rankings); $c++) {
            if($c == 0) :
              $text .= get_the_title($multuple_rankings[$c]);
            elseif($c+1 == count($multuple_rankings)) :
              $text .= ' and ' . get_the_title($multuple_rankings[$c]);
            else :
              $text .= ', ' . get_the_title($multuple_rankings[$c]);
            endif;
          }
          $messages['warnings'][] = array(
            'text' => 'This ballot has duplicate rankings for ' . $text . '. Typically, only the highest ranking will be counted.'
          );
        endif;




        uasort($casted_votes, function ($a, $b) { return $b['rank'] - $a['rank']; });
        $casted_votes = array_reverse($casted_votes);
        // array_multisort($votes, SORT_ASC, $casted_votes);



        if(!isset($messages['error']) && !isset($messages['warnings'])) :
          $text = '';
          foreach ($casted_votes as $key => $vote) :
            $text .= '<li>' . get_the_title($vote['candidate']) . '</li>';
          endforeach;
          $messages['success'][] = array(
            'text' => 'Congratulations! This is an error free ballot! Here\'s how you ranked the candidates: <ol>' . $text . '</ol>'
          );
        endif;

        foreach ($messages as $key => $message) :
          $return .= '<div class="alert ' . $key . '">';
          foreach ($message as $key => $item) :
            $return .=  $item['text'] . '<br />';
          endforeach;
          $return .= '</div>';
        endforeach;

        mapi_write_log($messages);

        wp_send_json_success( $return );

        // $post_data = array(
        //   'post_author'           => get_current_user_id(),
        //   'post_content'          => '',
        //   'post_title'            => '',
        //   'post_excerpt'          => '',
        //   'post_status'           => 'publish',
        //   'post_type'             => 'submitted_ballot',
        //   'post_parent'           => '',
        //   'context'               => '',
        //   'meta_input'            => $meta_array,
        // );

      } else {
        wp_send_json_error(array(
          'text' => '<div class="alert error">Looks like you didn\'t make any selections. Blank ballots cannot be counted.</div>',
          'class' => 'alert warning'
        ));
      }

    }
  }

}



new rcvAjax();
