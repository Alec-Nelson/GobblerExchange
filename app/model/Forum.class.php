<?php

class Forum extends DbObject {
    const DB_TABLE = "forum";

    //database fields
    protected $id;

    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
    }

    public static function loadById($id){
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    public function getPosts(){
        return ForumPost::getAllPosts($this->id);
    }

    public function getPinnedPosts(){
        return ForumPost::getAllPinnedPosts($this->id);
    }
}
?>
