<?php


class rcvBallot {
  private $options = '';
  private $token = '';

  function __construct() {


  }
  private function define( $name, $value ) {
    if ( ! defined( $name ) ) {
      define( $name, $value );
    }
  }

  public function show_ballot() {
    $candidates = $this->get_candidates();

    if($candidates) :


      $html = '';
      $html .= '<div class="ballot-table-responsive">';
      $html .= '<table id="ballotTable" class="practice-ballot live">';
        $html .= '<thead>';
          $html .= '<tr class="notranslate">';
            $html .= '<th scope="col" class="names"><small>Candidate Name.</small></td>';
            for ($c = 1; $c <= count($candidates); $c++) {
              $html .= '<th scope="col" class="rank">' . rcv_ordinal($c) . '<br></td>';
            }

          $html .= '</tr>';

        $html .= '</thead>';

        $html .= '<tbody>';
          foreach ($candidates as $key => $candidate) :
            $c_options = get_field('candidate_options', $candidate->ID);
            $img_arrt = array(
              'class' => 'candidate-icon me-2',
              'width' => '50px',
              'height' => '50px'
            );

            $html .= '<tr class="notranslate" title="' . $candidate->post_title . '" candidate="' . $candidate->ID . '">';
              $html .= '<td class="names">';

                $html .= '<div class="name-cont">';
                  $html .= '<span class="image" style="color:' . $c_options['candidate_color'] . '">';
                    $html .= ($c_options['candidate_icon_image'] ? wp_get_attachment_image( $c_options['candidate_icon_image']['id'], 'ballot-thumbnail', true, $img_arrt) : '');
                  $html .= '</span>';

                  $html .= '<span class="name my-auto" style="color:' . $c_options['candidate_color'] . '">';
                    $html .= $candidate->post_title;
                  $html .= '</span>';
                $html .= "</div>";
              $html .= '</td>';

              for ($c = 1; $c <= count($candidates); $c++) {
                $html .= '<td class="option text-nowrap" vote="false" rank="' . $c . '" candidate="' . $candidate->ID . '"><span class="option-cont"></span></td>';
              }


            $html .= '</tr>';
          endforeach;

        $html .= '</tbody>';
      $html .= '</table>';

      $html .= '<div class="d-block d-md-none scroll-notice">Swipe to Vote <i class="fas fa-arrow-right"></i></div>';

      $html .= '</div>';


    endif;



    return (isset($html) ? $html : 'Please add some candidates for the practice ballot.');
  }



  public function get_candidates() {
    $candidates = new WP_Query(array(
      'post_type' => 'rcv_candidates',
      'posts_per_page' => -1,
      // 'orderby' => 'rand',
    ));
    if($candidates->have_posts()) :
      return $candidates->posts;
    else :
      return false;
    endif;
    wp_reset_query();
  }

}
