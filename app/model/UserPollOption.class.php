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
}
?>
