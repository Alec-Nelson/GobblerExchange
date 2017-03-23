<?php

class Event extends DbObject {
    const DB_TABLE = "event";

    //database fields
    protected $id;
    protected $timestamp; //date and time of event
    protected $userId;
    protected $location;
    protected $description;
    protected $calendarId;
    protected $title;

    //constructor
    public function __construct($args = array()){
        $defaultArgs = array(
            'id' => null,
            'timestamp' => null,
            'userId' => null,
            'location' => null,
            'description' => null,
            'calendarId' => null,
            'title' => null
        );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->timestamp = $args['timestamp'];
        $this->userId = $args['userId'];
        $this->location = $args['location'];
        $this->description = $args['description'];
        $this->calendarId = $args['calendarId'];
        $this->title = $args['title'];
    }

    //save changes to database
    public function save(){
        $db = Db::instance();

        $db_properties = array(
            'timestamp' => $this->timestamp,
            'userId' => $this->userId,
            'location' => $this->location,
            'description' => $this->description,
            'calendarId' => $this->calendarId,
            'title' => $this->title
        );

        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    // //SQL formatted date/time
    // public function getSQLTimestamp(){
    //     date("Y-m-d H:i:s", $this->timestamp);
    // }
    //
    // //getter for date in readable format, for example: 3 15 2017
    // public function getDate(){
    //     return date("m d Y", $this->timestamp);
    // }

    //converts sql date to readable date
    public function convertToReadableDate($timestamp){
        return date("m/d/Y", strtotime($timestamp));
    }

    //converts readable format for date (Y-m-d) and time (H:i) into SQl datetime
    public function convertToSQLDateTime($date, $time, $pm){
        list($year, $month, $day) = explode('-', $date);
        list($hour, $minute) = explode(':', $time);

        if($pm) $hour += 12;

        return date("Y-m-d H:i:s", mktime($hour, $minute, 0, $month, $day, $year));
    }

    //Getters for numeric month, day, and year
    public function getMonthNumber(){
        return date("m", $this->timestamp);
    }
    public function getDay(){
        return date("d", $this->timestamp);
    }
    public function getYear(){
        return date("Y", $this->timestamp);
    }

    //Getter for the time in a readable format (ex. 2:30)
    public function getTime(){
        return date("g:i A", strtotime($this->timestamp));
    }

    // //Getters for the numeric hour, minute
    // public function getHour(){
    //     return date("H", $this->timestamp);
    // }
    // public function getMinute(){
    //     return date("i", $this->timestamp);
    // }

    public function delete()
    {
        $db = Db::instance();
        $query = sprintf(" DELETE FROM %s WHERE id = '%s' ",
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

    // public static function getAllEventsByDate($calendarId, $timestamp){
    //     //get just the database
    //     $year = date("Y", $timestamp);
    //     $month = date("m", $timestamp);
    //     $day = date("d", $timestamp);
    //
    //     $start = date("Y-m-d H:i:s", mktime(0, 0, 0, $month, $day, $year));
    //     $end = date("Y-m-d H:i:s", mktime(23, 59, 59, $month, $day, $year));
    //
    //     $query = sprintf("SELECT * FROM %s WHERE calendarId=%s AND timestamp BETWEEN %s and %s",
    //         self::DB_TABLE,
    //         $calendarId,
    //         $start,
    //         $end
    //     );
    //
    //     $db = Db::instance();
    //     $result = $db->lookup($query);
    //     if(!mysql_num_rows($result))
    //         return null;
    //     else {
    //         $objects = array();
    //         while($row = mysql_fetch_assoc($result)) {
    //             $objects[] = self::loadById($row['id']);
    //         }
    //         return ($objects);
    //     }
    // }

    //Note: index month 1 - 12, not 0 - 11
    // public static function getAllEventsByMonth($calendarId, $month, $year){
    //
    //     $start = date("Y-m-d", mktime(0, 0, 0, $month, 1, $year));
    //     $end = date("Y-m-d", mktime(23, 59, 59, $month, 31, $year));
    //     if($month == 2){
    //                 $end = date("Y-m-d", mktime(23, 59, 59, $month, 28, $year));
    //     }
    //     else if($month == 4 || $month == 6 || $month == 9 || $month == 11){
    //         $end = date("Y-m-d", mktime(23, 59, 59, $month, 30, $year));
    //     }
    //
    //     $query = sprintf("SELECT * FROM %s WHERE calendarId=%s AND timestamp BETWEEN '%s' and '%s'",
    //         self::DB_TABLE,
    //         $calendarId,
    //         $start,
    //         $end
    //     );
    //
    //     $db = Db::instance();
    //     $result = $db->lookup($query);
    //     if(!mysql_num_rows($result))
    //         return null;
    //     else {
    //         $objects = array();
    //         while($row = mysql_fetch_assoc($result)) {
    //             $objects[] = self::loadById($row['id']);
    //         }
    //         return ($objects);
    //     }
    // }

    //Note: index month 1 - 12, not 0 - 11
    public static function getAllEventsAfterToday($calendarId){

        $today = date("Y-m-d", time());

        $query = sprintf("SELECT * FROM %s WHERE calendarId=%s AND timestamp >= NOW() ORDER BY timestamp ASC",
            self::DB_TABLE,
            $calendarId,
            $today
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

    // -------------------------------------------------------------------------

    //**This function can be called from the Calendar class.
    public function getAllEventsByCalendar($id){

        $query = sprintf("SELECT * FROM %s WHERE calendarId=%s ",
            self::DB_TABLE,
            $id
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
