<?php

class Notes extends DbObject {
    const DB_TABLE = "notes";

    //database fields
    protected $id;
    protected $title;
    protected $link;       //this is essentially the filename, like 'test.pdf'
    protected $userId;
    protected $timestamp;
    protected $ratingId;
    protected $groupId;


    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null,
            'title' => null,
            'link' => null,
            'userId' => null,
            'timestamp' => null,
            'ratingId' => null,
            'groupId' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->title = $args['title'];
        $this->link = $args['link'];
        $this->userId = $args['userId'];
        $this->timestamp = $args['timestamp'];
        $this->ratingId = $args['ratingId'];
        $this->groupId = $args['groupId'];
    }

    //save changes to database
    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'title' => $this->title,
            'link' => $this->link,
            'userId' => $this->userId,
            'timestamp' => $this->timestamp,
            'ratingId' => $this->ratingId,
            'groupId' => $this->groupId
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

    public function loadNotesByUser($userId){
        return Rating::loadByUserAndNotesId($userId, $this->id);
    }

    public function upvote($userId){
        UserRating::upvote($this->ratingId, $userId);
    }
    public function downvote($userId){
        UserRating::downvote($this->ratingId, $userId);
    }

    public function getRating(){
        return Rating::getNotesRating($this->id);
    }

    public function getComments(){
        return Comment::getAllCommentsByNotes($this->id);
    }

    //--------------------------------------------------------------------------

    //get all notes from a specific group
    //**This function can be called from the Group class.
    public function getAllNotes($groupId){
        $query = sprintf(" SELECT * FROM %s WHERE groupId=%s ORDER BY timestamp DESC",
            self::DB_TABLE,
            $groupId
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

    public function getAllNotes_SortDescRating($groupId){
        $query = sprintf(" SELECT notes.id, rating.rating FROM notes INNER JOIN rating ON notes.ratingId = rating.id where groupId=%s ORDER BY rating.rating desc",
            $groupId
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
