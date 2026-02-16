<?php

include 'libs/load.php';

if (isset($_GET['logout'])) {
    if (Session::isset("session_token")) {
        try {
            $Session = new UserSession(Session::get("session_token"));
            if ($Session->removeSession()) {
                echo "<h3> Previous session was successfully removed from database. </h3>";
            } else {
                echo "<h3> Previous session could not be removed from database. </h3>";
            }
        } catch (Exception $e) {
            // Already invalid or expired
            error_log("Logout error: " . $e->getMessage());
        }
    }
    Session::destroy();
    header("Location: " . get_config('base_path'));
    die();
} else {
    // Handle POST upload BEFORE rendering (PRG pattern to prevent form resubmission)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && Session::isAuthenticated() 
        && isset($_POST['post_text']) && isset($_FILES['post_image'])) {
        try {
            $image_tmp = $_FILES['post_image']['tmp_name'];
            $text = $_POST['post_text'];
            
            if (empty($text)) {
                throw new Exception("Please add some text to your post");
            }
            if ($_FILES['post_image']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Please select an image to upload");
            }
            if (!is_file($image_tmp)) {
                throw new Exception("Image file not found");
            }
            
            $post = Post::registerPost($text, $image_tmp);
            
            if ($post) {
                header("Location: " . get_config('base_path') . "?upload=success");
                die();
            }
        } catch (Exception $e) {
            // Store error in session so it survives the redirect
            Session::set('upload_error', $e->getMessage());
            header("Location: " . get_config('base_path') . "?upload=error");
            die();
        }
    }
    
    Session::renderPage();
}

