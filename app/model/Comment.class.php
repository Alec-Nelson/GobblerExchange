<?php

class Comment extends DbObject {
    const DB_TABLE = "comment";

    //database fields
    protected $id;
    protected $userId;
    protected $timestamp;
    protected $comment;

    //one or the other of these must be null
    protected $postId;
    protected $notesId;


    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null,
            'userId' => null,
            'timestamp' => null,
            'comment' => null,
            'postId' => null,
            'notesId' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->userId = $args['userId'];
        $this->timestamp = $args['timestamp'];
        $this->comment = $args['comment'];
        $this->postId = $args['postId'];
        $this->notesId = $args['notesId'];

    }

    //update (save changes to database)
    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'userId' => $this->userId,
            'timestamp' => $this->timestamp,
            'title' => $this->title,
            'comment' => $this->comment,
            'postId' => $this->postId,
            'notesId' => $this->notesId
        );

        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    public static function loadById($id){
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    public function delete()
    {
        $db = Db::instance();
        $query = sprintf(" DELETE FROM %s  WHERE id = '%s' ",
            self::DB_TABLE,
            $this->id
        );
        $ex = mysql_query($query);
        if(!$ex) die ('Query failed:' . mysql_error());
    }

    // -------------------------------------------------------------------------

    //Get all comments for a particular forum post
    //**This function can be called from the ForumPost class.
    private function getAllCommentsByPost($postId){
        $query = sprintf(" SELECT * FROM %s WHERE postId=%s",
            self::DB_TABLE,
            $postId
        );

        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = self::loadById($row['id']);
            }
            return ($objects);
        }
    }

    //Get all comments for particular notes
    //**This function can be called from the Notes class.
    private function getAllCommentsByNotes($notesId){
        $query = sprintf(" SELECT * FROM %s WHERE notesId=%s",
            self::DB_TABLE,
            $notesId
        );

        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = self::loadById($row['id']);
            }
            return ($objects);
        }
    }
}
?>
