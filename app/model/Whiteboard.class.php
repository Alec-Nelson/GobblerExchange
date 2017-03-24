<?php

class Whiteboard extends DbObject {
    const DB_TABLE = "whiteboard";

    //database fields
    protected $id;

    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
    }

    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'id' => $this->id
        );

        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    public static function loadById($id){
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }
}
?>
