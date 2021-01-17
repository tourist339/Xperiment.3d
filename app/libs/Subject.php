<?php

class Subject{

    private static $subjects=null;
    public static function  getSubjects(){
        if(!self::$subjects){

            self::$subjects=json_decode(file_get_contents(CONFIG."subjects.json"),true);

        }
        return self::$subjects;
    }
}

?>