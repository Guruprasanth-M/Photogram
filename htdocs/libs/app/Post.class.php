<?php

include_once __DIR__ . "/../traits/SQLGetterSetter.trait.php";

class Post
{
    use SQLGetterSetter;

    public static function registerPost($text, $image_tmp)
    {
        if (is_file($image_tmp) and exif_imagetype($image_tmp) !== false) {
            $author = Session::getUser()->getEmail();
            $image_name = md5($author . time()) . image_type_to_extension(exif_imagetype($image_tmp));
            $image_path = get_config('upload_path') . $image_name;
            
            if (move_uploaded_file($image_tmp, $image_path)) {
                $image_uri = "/images/$image_name";
                $db = Database::getConnection();
                $text_safe = $db->real_escape_string($text);
                $author_safe = $db->real_escape_string($author);
                $image_uri_safe = $db->real_escape_string($image_uri);
                $insert_command = "INSERT INTO `posts` (`post_text`, `mulit_image`, `image_uri`, `like_count`, `upload_time`, `owner`) VALUES ('$text_safe', '0', '$image_uri_safe', 0, NOW(), '$author_safe')";
                
                if ($db->query($insert_command)) {
                    $id = mysqli_insert_id($db);
                    return new Post($id);
                } else {
                    return false;
                }
            }
        } else {
            throw new Exception("Image not uploaded");
        }
    }

    public function __construct($id)
    {
        $this->id = $id;
        $this->conn = Database::getConnection();
        $this->table = 'posts';
    }
}
