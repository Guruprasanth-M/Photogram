<?php

include_once __DIR__ . "/../traits/SQLGetterSetter.trait.php";

class Like
{
    use SQLGetterSetter;

    public function __construct(Post $post)
    {
        $userid = Session::getUser()->getID();
        $postid = $post->getID();
        $this->id = md5($userid . "-" . $postid);
        $this->conn = Database::getConnection();
        $this->table = 'likes';

        $query = "SELECT * FROM `likes` WHERE `id` = '$this->id'";
        $result = $this->conn->query($query);

        if ($result->num_rows == 0) {
            $query_insert = "INSERT INTO `likes` (`id`, `user_id`, `post_id`, `like`, `timestamp`)
            VALUES ('$this->id', '$userid', '$postid', 0, now())";
            $result = $this->conn->query($query_insert);
            if ($result) {
                if (!$this->conn->query($query)) {
                    throw new Exception("Unable to create like entry");
                }
            }
        }
    }

    public function toggleLike()
    {
        $liked = $this->getLike();
        if (boolval($liked) == true) {
            $this->setLike(0);
        } else {
            $this->setLike(1);
        }
    }

    public function isLiked()
    {
        return boolval($this->getLike());
    }
}
