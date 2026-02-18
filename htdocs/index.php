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
                $upload_errors = [
                    UPLOAD_ERR_INI_SIZE   => 'File too large (server limit: ' . ini_get('upload_max_filesize') . ')',
                    UPLOAD_ERR_FORM_SIZE  => 'File too large (form limit exceeded)',
                    UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE    => 'No file was selected',
                    UPLOAD_ERR_NO_TMP_DIR => 'Server error: missing temp folder',
                    UPLOAD_ERR_CANT_WRITE => 'Server error: failed to write file',
                    UPLOAD_ERR_EXTENSION  => 'Upload blocked by a PHP extension',
                ];
                $err_code = $_FILES['post_image']['error'];
                $err_msg = $upload_errors[$err_code] ?? 'Unknown upload error (code: ' . $err_code . ')';
                throw new Exception($err_msg);
            }
            if (!is_file($image_tmp)) {
                throw new Exception("Image file not found");
            }
            
            $post = Post::registerPost($text, $image_tmp);
            
            if ($post) {
                // TODO: Remove dynamic base_path detection check once development is done
                //       This ensures redirect works on both domain and IP-based access
                header("Location: " . get_config('base_path') . "?upload=success");
                die();
            }
        } catch (Exception $e) {
            // Store error in session so it survives the redirect
            Session::set('upload_error', $e->getMessage());
            // TODO: Remove dynamic base_path detection check once development is done
            //       This ensures redirect works on both domain and IP-based access
            $redirect_url = get_config('base_path') . "?upload=error";
            error_log("Upload error redirect: " . $redirect_url . " | Error: " . $e->getMessage());
            header("Location: " . $redirect_url);
            die();
        }
    }
    
    Session::renderPage();
}

