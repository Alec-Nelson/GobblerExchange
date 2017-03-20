<?php

//A particular option for a poll
class PollOption extends DbObject {
    const DB_TABLE = "polloption";

    //database fields
    protected $id;
    protected $pollId;
    protected $poll_option;  //text of the option

    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null,
            'pollId' => null,
            'poll_option' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->pollId = $args['pollId'];
        $this->poll_option = $args['poll_option'];
    }

    //save changes to database
    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'pollId' => $this->pollId,
            'poll_option' => $this->poll_option
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

    // -------------------------------------------------------------------------
    //**This function can be called from the Poll class.
    public function getPollOptions($pollId){
        $query = sprintf(" SELECT * FROM %s WHERE pollId=%s",
            self::DB_TABLE,
            $pollId
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
