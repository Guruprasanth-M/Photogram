<?php

include_once __DIR__ . "/../traits/SQLGetterSetter.trait.php";

class Post {
    use SQLGetterSetter;

    private $id;
    private $conn;
    private $table = 'posts';

    public static function registerPost($text, $image_tmp) {
        if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK) {
            $author = Session::getUser()->getUsername();
            $image_name = md5($author . time()) . ".jpg";
            $image_path = get_config('upload_path') . '/' . $image_name;

            if (move_uploaded_file($image_tmp, $image_path)) {
                $db = Database::getConnection();
                $text_safe = $db->real_escape_string($text);
                $author_safe = $db->real_escape_string($author);

                $sql = "INSERT INTO `posts` (`post_text`, `image_uri`, `like_count`, `upload_time`, `owner`)
                        VALUES ('$text_safe', '$image_name', '0', NOW(), '$author_safe')";

                if ($db->query($sql)) {
                    $id = mysqli_insert_id($db);
                    return new Post($id);
                } else {
                    return false;
                }
            } else {
                throw new Exception("Failed to move uploaded file.");
            }
        } else {
            throw new Exception("Image not uploaded.");
        }
    }

    public function __construct($id) {
        $this->id = $id;
        $this->conn = Database::getConnection();
    }
}