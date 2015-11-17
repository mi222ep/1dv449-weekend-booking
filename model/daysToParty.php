<?php
namespace model;
require_once("confirmedPlan.php");
class daysToParty{
    private $nameOfDay;
    private $shortNameOfDay;
    private $movies = array();
    private $barTableTimes = array();

    public function __construct($day, $short){
        $this->nameOfDay = $day;
        $this->shortNameOfDay = $short;
    }
    public function getNameOfDay(){
        return $this->nameOfDay;
    }
    public function getShortName(){
        return $this->shortNameOfDay;
    }
    public function addMovie($movie, $time){
        $this->movies[] = array("film" =>$movie, "tid" => $time);
    }
    public function addBarTableTimes($time){
        $this->barTableTimes[] = $time;
    }
    public function getTodaysPlans(){
        $plans = array();
        foreach($this->movies as $movies){
            $movieHour = substr($movies["tid"], 0, 2);
            foreach($this->barTableTimes as $time){
                if(substr($time, 0, 2) - $movieHour == 2){
                    $plans[] = new confirmedPlan($this->nameOfDay,$movies["tid"], $movies['film']);
                    //$plans[] = array("day"=>$this->nameOfDay, "movie"=>$movies['film'], "time"=>$movies["tid"]);
                }
            }
        }
        return $plans;
    }
}