<?php

//
// class rcvOptions {
//   public function __construct() {
//     add_action( 'admin_menu', array('rcvOptions','rcv_ballot_support_settings_page' ));
//     add_action( 'admin_init', array('rcvOptions','rcv_ballot_api_settings_init' ));
// 	}
//
//
//   static function rcv_ballot_support_settings_page() {
//       add_options_page(
//         'Ballot Settings',
//         'Ballot Settings',
//         'manage_options', //permisions
//         'rcv-settings', //page slug
//         'rcv_ballot_support_settings' //callback for display
//       );
//   }
//
//
//   static function rcv_ballot_api_settings_init(  ) {
//
//   }
//
//
//   static function rcv_ballot_setting_field($args) {
//
//   }
//
//
//   static function rcv_ballot_support_settings_section_callback() {
//
//   }
//
//
//
// }
//
//
//
//
// function rcv_ballot_processRound($castedBallots, $candidateCounts = array(), $round = 1) {
//
//   $needed_to_win = ceil(count($castedBallots) / 2);
//   $eliminated = array();
//   if($needed_to_win) {
//     echo '<h3>Round: ' . $round . '</h3>';
//     if($round == 1) :
//       echo '<span class="needed-to-win">Votes needed to win: ' . $needed_to_win . '</span>';
//     endif;
//
//     foreach ($castedBallots as $user => $choices) :
//       if(isset($choices[$round])) :
//         $votes = ( array_key_exists(trim($choices[$round]), $candidateCounts) ? $candidateCounts[trim($choices[$round])] : 0 );
//         $candidateCounts[trim($choices[$round])] = $votes + 1;
//       endif;
//     endforeach;
//
//     echo '<ul>';
//     foreach ($candidateCounts as $can => $votes) :
//       echo '<li>' . $can . ': <span class="count">' . $votes . '</span></li>';
//       if($votes >= $needed_to_win) :
//         echo '<h2>Winner: ' . $can , '</h2>';
//         return $can; //this is our winner!
//       else :
//
//         $lowest_voted_candidates = array_keys($candidateCounts, min($candidateCounts));
//
//         foreach ($castedBallots as $user => $value) :
//           if(isset($value[$round])) :
//             if($value[$round] == $can && in_array($can, $lowest_voted_candidates)) :
//               $next_round_vote = $castedBallots[$user][$round+1];
//               $votes = ( array_key_exists($next_round_vote, $candidateCounts) ? $candidateCounts[$next_round_vote] : 0 );
//               $candidateCounts[$next_round_vote] = $votes + 1;
//               $eliminated[] = $can;
//               unset($candidateCounts[$can]);
//             endif;
//           endif;
//
//         endforeach;
//
//       endif;
//     endforeach;
//     echo '</ul>';
//
//     if(count($eliminated) > 0) {
//       echo 'Eliminated Candidates: ' . implode(', ', $eliminated);
//     }
//     echo '<hr>';
//     rcv_ballot_processRound($castedBallots, $candidateCounts, $round + 1);
//   }
//
// }
//
// function rcv_ballot_support_settings() {
//   $castedBallots = new WP_Query(array(
//     'post_type' => 'submitted_ballot',
//     'posts_per_page' => -1,
//     'order' => 'ASC'
//   ));
//   if($castedBallots->have_posts()) :
//
//     $rounds = array();
//     $userCounts = array();
//
//
//     echo '<div class="rcvPage">';
//       while($castedBallots->have_posts()) :
//         $castedBallots->the_post();
//
//
//         $userID = get_post_meta(get_the_id(), 'userUID', true);
//         $casted_votes = get_post_meta(get_the_id(), 'casted_votes', true);
//         if($casted_votes && $userID) :
//
//           //Count submissions by user
//           if(isset($userCounts[$userID])) :
//             $count = $userCounts[$userID];
//             $userCounts[$userID] = $count+1;
//           else :
//             $userCounts[$userID] = 1;
//           endif;
//
//
//           foreach ($casted_votes as $rank => $candidate) :
//             $rounds[$userID][$rank] = trim($candidate);
//           endforeach;
//         endif;
//       endwhile;
//
//       echo '<div class="inside">';
//
//         rcv_ballot_processRound($rounds);
//         echo '<hr>';
//         echo '<h4>Other Analytics</h4>';
//         echo '<ul>';
//           echo '<li>Ballot Practices: ' . $userCounts['practice'] . '</li>';
//           unset($userCounts['practice']);
//           $average_submitted_ballots = array_sum($userCounts) / count($userCounts);
//           echo '<li>Average Number of Submitted Ballots: ' . $average_submitted_ballots . '</li>';
//         echo '</ul>';
//       echo '</div>';
//
//     echo '</div>';
//   else :
//     echo '<h4>No ballots have been cast.</h4>';
//   endif;
//
//
// }
//
//
//
// new rcvOptions();
