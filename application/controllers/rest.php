<?php

require(APPPATH . '/libraries/REST_Controller.php');

class Rest extends REST_Controller {

  public function __construct() {
    parent::__construct();
    $this->config->load('bookswap');
    $this->user = $this->session->userdata('bookswap_user');
  }

  /**
   * Handles updating a post in the database via the HTTP POST method.
   *
   * Currently, we allow updating only a post's "active" status.
   *
   * @param int $pid The ID of the post to update
   */
  public function post_post($pid) {
    $post = $this->post_model->get_posts(array('pid' => $pid));
    if ($post->uid != $this->user->uid) {
      $response = array(
        'errors' => array('You do not have permission to update this post.'),
      );
      $this->response($response, 403);
    }
    $active = $this->post('active');
    $this->post_model->update_post(array(
      'pid' => $pid,
      'active' => ($active == 'true' || $active > 0),
    ));
    $this->response(array(), 200);
  }

}
