<?php

class Rating extends DbObject {
    const DB_TABLE = "rating";

    //database fields
    protected $id;
    protected $rating;

    //one or the other of these must be null
    // protected $postId;
    // protected $notesId;

    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null,
            'rating' => null
            // ,
            // 'postId' => null,
            // 'notesId' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->rating = $args['rating'];
        // $this->postId = $args['postId'];
        // $this->notesId = $args['notesId'];
    }

    //save changes to database
    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'rating' => $this->rating
            // ,
            // 'postId' => $this->postId,
            // 'notesId' => $this->notesId
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
       $query = sprintf(" DELETE FROM %s WHERE id = '%s'",
           self::DB_TABLE,
           $this->id
       );
       mysql_query($query);
   }
}
?>
