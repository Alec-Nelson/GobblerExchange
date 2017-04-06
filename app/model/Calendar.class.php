<?php

class Calendar extends DbObject {
    const DB_TABLE = "calendar";

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

    public function getEvents(){
        return Event::getAllEventsByCalendar($this->id);
    }

    public function getEventsByMonth($month, $year){
        return Event::getAllEventsByMonth($this->id, $month, $year);
    }

    public function getAllEventsAfterToday(){
        return Event::getAllEventsAfterToday($this->id);
    }

    public function getAllEventsBeforeToday(){
        return Event::getAllEventsBeforeToday($this->id);
    }
}
?>
