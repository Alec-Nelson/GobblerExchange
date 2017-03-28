<?php

class UserRating extends DbObject {
    const DB_TABLE = "userrating";

    //database fields
    protected $id;
    protected $userId;
    protected $ratingId;
    protected $vote;

    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null,
            'userId' => null,
            'ratingId' => null,
            'vote' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->userId = $args['userId'];
        $this->ratingId = $args['ratingId'];
        $this->vote = $args['vote'];
    }

    //save changes to database
    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'userId' => $this->userId,
            'ratingId' => $this->ratingId,
            'vote' => $this->vote
        );

        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
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

    public function loadById($id){
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    public static function loadByUserAndRatingId($userId, $ratingId) {
        $query = sprintf(" SELECT * FROM %s WHERE ratingId = '%s' AND userId='%s' ",
            self::DB_TABLE,
            $ratingId,
            $userId
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $row = mysql_fetch_assoc($result);
            $obj = self::loadById($row['id']);
            return ($obj);
        }
    }

   //upvote
   public function upvote($ratingId, $userId){
       $old_rating = self::loadByUserAndRatingId($userId, $ratingId);

       if($old_rating != null){
           //user has previously downvoted the post - remove (neutralize) their rating
           $old_rating->delete();
       }
       else {
           //user has not rated the post; record their vote as +1
           $rating = new UserRating();
           $rating->set('userId', $userId);
           $rating->set('ratingId', $ratingId);
           $rating->set('vote', 1);
           $rating->save();
       }


      //update rating
      $rate = Rating::loadById($ratingId);
      $old_score = $rate->get('rating');
      $rate->set('rating', $old_score + 1);
      $rate->save();
   }

   //Downvote
   public function downvote($ratingId, $userId){
       $old_rating = self::loadByUserAndRatingId($userId, $ratingId);

       if($old_rating != null){
           //user has previously upvoted the post - remove (neutralize) their rating
           $old_rating->delete();
       }
       else {
           //user has not rated the post; record their vote as +1
           $rating = new UserRating();
           $rating->set('userId', $userId);
           $rating->set('ratingId', $ratingId);
           $rating->set('vote', -1);
           $rating->save();
       }

       //update rating
       $rate = Rating::loadById($ratingId);
       $old_score = $rate->get('rating');
       $rate->set('rating', $old_score - 1);
       $rate->save();
   }
}
?>
