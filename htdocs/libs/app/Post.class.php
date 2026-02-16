<?php

include_once __DIR__ . "/../traits/SQLGetterSetter.trait.php";

use Carbon\Carbon; //including a namespace

class Post
{
    use SQLGetterSetter; //including a trait

    public static function registerPost($text, $image_tmp)
    {
        // Check if file was uploaded
        if (!is_file($image_tmp)) {
            throw new Exception("No image file was uploaded. Temp path: " . $image_tmp);
        }
        
        // Check if it's a valid image
        $image_type = exif_imagetype($image_tmp);
        if ($image_type === false) {
            throw new Exception("Invalid image file. Please upload a valid image (JPG, PNG, GIF)");
        }
        
        $author = Session::getUser()->getEmail();
        $image_name = md5($author . time()) . image_type_to_extension($image_type);
        $image_path = get_config('upload_path') . '/' . $image_name;
        
        if (move_uploaded_file($image_tmp, $image_path)) {
            $db = Database::getConnection();
            $text_safe = $db->real_escape_string($text);
            $author_safe = $db->real_escape_string($author);
            $image_uri = get_config('base_path') . 'uploads/' . $image_name;
            $image_uri_safe = $db->real_escape_string($image_uri);
            
            $insert_command = "INSERT INTO posts (post_text, mulit_image, image_uri, like_count, upload_time, owner) VALUES ('$text_safe', '0', '$image_uri_safe', 0, NOW(), '$author_safe')";
            
            if ($db->query($insert_command)) {
                $id = mysqli_insert_id($db);
                return new Post($id);
            } else {
                throw new Exception("Database error: " . $db->error);
            }
        } else {
            throw new Exception("Failed to move uploaded file to: " . $image_path);
        }
    }

    public static function getAllPosts()
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM `posts` ORDER BY `upload_time` DESC";
        $result = $db->query($sql);
        return iterator_to_array($result);
    }

    public function __construct($id)
    {
        $this->id = $id;
        $this->conn = Database::getConnection();
        $this->table = 'posts';
    }
}
