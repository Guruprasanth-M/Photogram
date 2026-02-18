<?php

// POST /api/posts/content/down
${basename(__FILE__, '.php')} = function () {
    $result = [
        "success" => false,
        "message" => "Downvoted the post"
    ];
    $this->response($this->json($result), 200);
};
