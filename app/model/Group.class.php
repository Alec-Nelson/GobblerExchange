<?php

class Group extends DbObject {
    const DB_TABLE = "groups"; //SQL doesn't like "group" since it's a keyword

    //database fields
    protected $id;
    protected $number;      //crn, if a class, null otherwise
    protected $group_name;
    protected $calendarId;
    protected $forumId;
    protected $chatId;
    protected $whiteboardId;
    protected $userId;      //group leader

    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null,
            'number' => null,
            'group_name' => null,
            'calendarId' => null,
            'forumId' => null,
            'chatId' => null,
            'whiteboardId' => null,
            'userId' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->number = $args['number'];
        $this->group_name = $args['group_name'];
        $this->calendarId = $args['calendarId'];
        $this->forumId = $args['forumId'];
        $this->chatId = $args['chatId'];
        $this->whiteboardId = $args['whiteboardId'];
        $this->userId = $args['userId'];
    }

    //save changes to database
    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'number' => $this->number,
            'group_name' => $this->group_name,
            'calendarId' => $this->calendarId,
            'forumId' => $this->forumId,
            'chatId' => $this->chatId,
            'whiteboardId' => $this->whiteboardId,
            'userId' => $this->userId
        );

        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    public static function loadById($id){
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    //load by CRN
    public static function loadByCRN($crn) {
        if($crn === null)
            return null;
        $query = sprintf(" SELECT id FROM %s WHERE number = '%s' ",
            self::DB_TABLE,
            $crn
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

    //checks if the group name has already been taken
    public static function checkGroupNameAvailability($groupname){
        if($groupname === null)
            return false;
        $query = sprintf(" SELECT id FROM %s WHERE group_name = '%s' ",
            self::DB_TABLE,
            $groupname
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return true;
        else {
            return false;
        }
    }

    //get all users in this group
    public function getUsers(){
        return UserGroup::getAllUsersInGroup($this->id);
    }

    //get all notes for this group
    public function getNotes(){
        return Notes::getAllNotes($this->id);
    }

    //get all polls for this group
    public function getAllPolls(){
        return Poll::getAllPolls($this->id);
    }

    //SEARCH FUNCTIONS ---------------------------------------------------

    //search by CRN
    public static function searchCRN($crn) {
        $query = sprintf(" SELECT id FROM %s WHERE number='%s'",
            self::DB_TABLE,
            $crn
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

    //search by group name
    public static function searchGroupName($group_name) {
        $query = sprintf(" SELECT id FROM %s WHERE group_name='%s'",
            self::DB_TABLE,
            $group_name
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
}
?>
