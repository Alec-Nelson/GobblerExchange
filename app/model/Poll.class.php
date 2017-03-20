<?php

class Poll extends DbObject {
    const DB_TABLE = "poll";

    //database fields
    protected $id;
    protected $title;
    protected $userId;     //author
    protected $groupId;
    protected $timestamp;
    protected $isOpen;

    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null,
            'title' => null,
            'userId' => null,
            'groupId' => null,
            'timestamp' => null,
            'isOpen' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->title = $args['title'];
        $this->userId = $args['userId'];
        $this->groupId = $args['groupId'];
        $this->timestamp = $args['timestamp'];
        $this->isOpen = $args['isOpen'];
    }

    //save changes to database
    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'title' => $this->title,
            'userId' => $this->userId,
            'groupId' => $this->groupId,
            'timestamp' => $this->timestamp,
            'isOpen' => $this->isOpen
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

    public static function loadById($id){
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    public function getPollOptions(){
        return PollOptions::getPollOptions($this->id);
    }

    public function openPoll($pollId){
        $poll = Poll::loadById($pollId);
        $poll->set('isOpen', true);
        $poll->save();
    }
    public function closePoll($pollId){
        $poll = Poll::loadById($pollId);
        $poll->set('isOpen', false);
        $poll->save();
    }

    //get all polls for a group
    //**This function can be called from the Group class.
    public function getAllPolls($groupId){
        $query = sprintf(" SELECT * FROM %s WHERE groupId=%s ORDER BY timestamp DESC",
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

    //get all open polls for a group, starting with the most recent
    //**This function can be called from the Group class.
    public function getAllOpenPolls($groupId){
        $query = sprintf(" SELECT * FROM %s WHERE groupId='%s' AND isOpen='true' ORDER BY timestamp DESC",
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
}
?>
