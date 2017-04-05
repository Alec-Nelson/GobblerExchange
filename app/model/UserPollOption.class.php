<?php

class UserPollOption extends DbObject {
    const DB_TABLE = "userpolloption";

    //database fields
    protected $id;
    protected $pollOptionId;
    protected $userId;

    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null,
            'pollOptionId' => null,
            'userId' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->pollOptionId = $args['pollOptionId'];
        $this->userId = $args['userId'];
    }

    //save changes to database
    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'pollOptionId' => $this->pollOptionId,
            'userId' => $this->userId
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

    //load by polloptionid nad userid
    public function loadByPollOptionAndUser($optId, $userId) {
        if($optId === null || $userId === null)
            return null;
        $query = sprintf(" SELECT id FROM %s WHERE pollOptionId = '%s' AND userId = '%s'",
            self::DB_TABLE,
            $optId,
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

    //gets the user's old selection for this poll
    public static function getOldSelection($pollId, $userId) {
        if($pollId === null || $userId === null)
            return null;

        //get all options in poll
        $poll = Poll::loadById($pollId);
        $options = $poll->getPollOptions($pollId);
        foreach($options as $option){
            $id = $option->get('id');
            if ($userpollopt = self::loadByPollOptionAndUser($id, $userId)){
                return $userpollopt;
            }
        }
        return null;
    }

    public function getTotalVotesByPollOptionId($pollOptionId){
        $query = sprintf(" SELECT * FROM %s WHERE pollOptionId=%s",
            self::DB_TABLE,
            $pollOptionId
        );

        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return 0;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = self::loadById($row['id']);
            }
            return count($objects);
        }
    }
}
?>
