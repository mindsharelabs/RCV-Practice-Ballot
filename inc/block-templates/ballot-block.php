<?php

/**
 * Ballot Block
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'ballot-block-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'ballot-block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align aligncenter';
}

if($is_preview) :
  echo '<div style="background:#eeeeee; text-align:center; padding: 10px">The Practice ballot will display here</div>';
else :
  $ballot = new rcvBallot();
  $practice_ballot = get_field('practice_ballot');


    echo '<div id="practiceBallot">';
      echo $ballot->show_ballot();
    echo '</div>';

    echo '<div class="' . $className . '">';
      echo'<div>';
        echo '<div id="ballotFeedback"></div>';
      echo'</div>';

      echo'<div class="interaction-container">';
        echo'<button class="btn button" id="testBallot">Test This Ballot</button></br>';
        echo'<button class="btn button" id="ballotReset">Reset Ballot</button>';
      echo'</div>';
    echo'</div>';



endif;
