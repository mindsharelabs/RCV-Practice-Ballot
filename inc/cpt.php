<?php

add_action('init', 'rcv_ballot_advisory_create_post_type'); // Add our mind Blank Custom Post Type
function rcv_ballot_advisory_create_post_type() {
  register_post_type('rcv_candidates', // Register Custom Post Type
  array(
    'labels' => array(
      'name' => __('RCV Candidates', 'mindblank'), // Rename these to suit
      'singular_name' => __('RCV Candidates', 'mindblank'),
      'add_new' => __('Add New Candidate', 'mindblank'),
      'add_new_item' => __('Add New Candidate', 'mindblank'),
      'edit' => __('Edit Candidate', 'mindblank'),
      'edit_item' => __('Edit Candidate', 'mindblank'),
      'new_item' => __('New Candidate', 'mindblank'),
      'view' => __('View Candidate', 'mindblank'),
      'view_item' => __('View Candidate', 'mindblank'),
      'search_items' => __('Search Candidates', 'mindblank'),
      'not_found' => __('No Candidates found', 'mindblank'),
      'not_found_in_trash' => __('No Candidates found in Trash', 'mindblank')
    ),
    'public' => true,
    'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
    'has_archive' => false,
    'show_in_rest' => true,
    'supports' => array(
      'title',
      'thumbnail'
    ), // Go to Dashboard Custom mind Blank post for supports
    'can_export' => true, // Allows export in Tools > Export
  ));

  register_post_type('submitted_ballot', // Register Custom Post Type
  array(
    'labels' => array(
      'name' => __('Submitted Ballot', 'mindblank'), // Rename these to suit
      'singular_name' => __('Submitted Ballots', 'mindblank'),
      'add_new' => __('Add New Submitted Ballot', 'mindblank'),
      'add_new_item' => __('Add New Ballot', 'mindblank'),
      'edit' => __('Edit Ballot', 'mindblank'),
      'edit_item' => __('Edit Ballot', 'mindblank'),
      'new_item' => __('New Ballot', 'mindblank'),
      'view' => __('View Ballot', 'mindblank'),
      'view_item' => __('View Ballot', 'mindblank'),
      'search_items' => __('Search Ballots', 'mindblank'),
      'not_found' => __('No Ballots found', 'mindblank'),
      'not_found_in_trash' => __('No Ballots found in Trash', 'mindblank')
    ),
    'public' => false,
    'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
    'has_archive' => false,
    'show_in_rest' => false,
    'supports' => array(), // Go to Dashboard Custom mind Blank post for supports
    'can_export' => true, // Allows export in Tools > Export
  ));


}
