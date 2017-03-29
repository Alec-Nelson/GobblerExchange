<?php

class UserGroup extends DbObject {
    const DB_TABLE = "usergroup";

    //database fields
    protected $id;
    protected $userId;
    protected $groupId;

    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null,
            'userId' => null,
            'groupId' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->userId = $args['userId'];
        $this->groupId = $args['groupId'];
    }

    //save changes to database
    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'userId' => $this->userId,
            'groupId' => $this->groupId
        );

        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    public static function loadById($id){
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    public static function isUserInGroup($userId, $groupId) {
        $query = sprintf(" SELECT id FROM %s WHERE userId='%s' AND groupId='%s'",
            self::DB_TABLE,
            $userId,
            $groupId
            );

        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return false;
        else {
            return true;
        }
    }

    //Get a list of all users in a particular group
    //**This function can be called from the Group class.
    public function getAllUsersInGroup($groupId){
        $query = sprintf(" SELECT * FROM %s WHERE groupId=%s",
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

    //Get a list of all groups a particular user is a part of
    //**This function can be called from the User class.
    public function getAllGroupsOfUser($userId){
        $query = sprintf(" SELECT * FROM %s WHERE userId=%s",
            self::DB_TABLE,
            $userId
        );

        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = Group::loadById($row['groupId']);
            }
            return ($objects);
        }
    }
}
?>
