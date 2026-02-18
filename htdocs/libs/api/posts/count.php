<?php

// GET /api/posts/count
${basename(__FILE__, '.php')} = function () {
    $this->response($this->json(Post::countAllPosts()[0]), 200);
};
