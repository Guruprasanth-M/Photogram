<?php

// POST /api/posts/like
${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() and $this->paramsExists('id')) {
        $this->response($this->json([
            'message' => "success"
        ]), 200);
    } else {
        $this->response($this->json([
            'message' => "bad request"
        ]), 400);
    }
};
