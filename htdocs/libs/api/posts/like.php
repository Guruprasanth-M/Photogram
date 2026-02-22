<?php

// POST /api/posts/like
${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() and $this->paramsExists('id')) {
        $post = new Post($this->_request['id']);
        $like = new Like($post);
        $like->toggleLike();
        $this->response($this->json([
            'liked' => $like->isLiked()
        ]), 200);
    } else {
        $this->response($this->json([
            'message' => "bad request"
        ]), 400);
    }
};
