<?php
namespace model;
require_once("daysToParty.php");
class calendarModel{
    function analyzeTableFromCalendar($arr){

        $saturdayOK = true;
        $fridayOK = true;
        $sundayOK = true;

        for( $i =0; $i<sizeof($arr); $i++){
            if($arr[$i]["Friday"] == false){
                $fridayOK = false;
            }
            if($arr[$i]["Saturday"] == false){
                $saturdayOK = false;
            }
            if($arr[$i]["Sunday"] == false){
                $sundayOK = false;
            }
        }
        $days = array();
        if($fridayOK){
            $day = new \model\daysToParty("Fredag", "fre");
            array_push($days, $day);
        }
        if($saturdayOK){
            $day = new \model\daysToParty("Lördag", "lor");
            array_push($days, $day);
        }
        if($sundayOK){
            $day = new \model\daysToParty("Söndag", "son");
            array_push($days, $day);
        }
        return $days;
    }
}