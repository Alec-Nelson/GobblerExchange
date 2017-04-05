<?php

class ForumPost extends DbObject {
    const DB_TABLE = "forumpost";
    const tags = array( 'Homework',
                        'Project',
                        'Test',
                        'Notes',
                        'Help',
                        'Meeting',
                        'Discussion',
                        'Other'
                    );

    //database fields
    protected $id;
    protected $userId;
    protected $timestamp;
    protected $title;
    protected $description;
    protected $ratingId;
    protected $tag;
    protected $pinned;          //bool, 0 or 1
    protected $forumId;


    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null,
            'userId' => null,
            'timestamp' => null,
            'title' => null,
            'description' => null,
            'ratingId' => null,
            'tag' => null,
            'pinned' => null,
            'forumId' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->userId = $args['userId'];
        $this->timestamp = $args['timestamp'];
        $this->title = $args['title'];
        $this->description = $args['description'];
        $this->ratingId = $args['ratingId'];
        $this->tag = $args['tag'];
        $this->pinned = $args['pinned'];
        $this->forumId = $args['forumId'];

    }

    //update (save edits/changes to database)
    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'userId' => $this->userId,
            'timestamp' => $this->timestamp,
            'title' => $this->title,
            'description' => $this->description,
            'ratingId' => $this->ratingId,
            'tag' => $this->tag,
            'pinned' => $this->pinned,
            'forumId' => $this->forumId
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

    public function loadPostByUser($userId){
        return Rating::loadByUserAndPostId($userId, $this->id);
    }

    //userid - the userid of the person who is upvoting, not the author
    public function upvote($userId){
        UserRating::upvote($this->ratingId, $userId);
    }
    //userid - the userid of the person who is downvoting, not the author
    public function downvote($userId){
        UserRating::downvote($this->ratingId, $userId);
    }
    public function getComments(){
        return Comment::getAllCommentsByPost($this->id);
    }

    //-------------------------------------------------------------------------

    //get all posts for a group's forum
    //**This function can be called from the Forum class.
    public function getAllPosts($forumId){
        $query = sprintf(" SELECT * FROM %s WHERE forumId=%s ORDER BY timestamp DESC",
            self::DB_TABLE,
            $forumId
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

    //returns all non-pinned posts, ordered from higher rated posts to lower rated posts
    public function getAllPosts_SortDescRating($forumId){
        $query = sprintf(" SELECT forumpost.id, rating.rating FROM forumpost INNER JOIN rating ON forumpost.ratingId = rating.id where forumId=%s AND pinned=0 ORDER BY rating.rating desc",
            $forumId
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

    //**This function can be called from the Forum class.
    public function getAllPinnedPosts($forumId){
        $query = sprintf(" SELECT * FROM %s WHERE forumId=%s AND pinned=1 ORDER BY timestamp DESC",
            self::DB_TABLE,
            $forumId
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

    //Seaches posts with search_term present in
    //the title or description
    public function searchByTitleAndDesc($search_term, $forumId){
      //search by group name
      //returns all posts if search term is empty/null
          if($search_term == null || $search_term == ""){
              return self::getAllPosts($forumId);
          }
          $query = sprintf(" SELECT id FROM %s WHERE title LIKE '%%%s%%' OR description LIKE '%%%s%%' ",
              self::DB_TABLE,
              $search_term,
              $search_term
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
