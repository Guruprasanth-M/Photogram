<?php

use Carbon\Carbon;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() and $this->paramsExists('post_text') and isset($_FILES['post_image'])) {
        $image_tmp = $_FILES['post_image']['tmp_name'];
        $text = $this->_request['post_text'];
        $post = Post::registerPost($text, $image_tmp);
        $owner = Session::getUser();

        ob_start();
        Session::loadTemplate('index/photocard', [
            'p' => $post,
            'uploaded_time_str' => Carbon::parse($post->getUploadTime())->diffForHumans(),
            'owner' => $owner
        ]);
        $html = ob_get_clean();
        
        $this->response($html, 200);
    } else {
        $this->response($this->json([
            'message' => "bad request"
        ]), 400);
    }
};
