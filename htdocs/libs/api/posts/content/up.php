<?php

// POST /api/posts/content/up
${basename(__FILE__, '.php')} = function () {
    $result = [
        "success" => false,
        "auth" => $this->isAuthenticated(),
        "message" => "Upvote the post"
    ];
    $this->response($this->json($result), 200);
};
