<?php
class daysToParty{
    private $nameOfDay;
    private $shortNameOfDay;
    private $movies = array();
    private $barTableTimes = array();

    public function daysToParty($day, $short){
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
        for($i = 0; $i < sizeof($this->movies); $i++){
            $movieHour = substr($this->movies[$i]["tid"], 0, 2);
            foreach($this->barTableTimes as $time){
                if(substr($time, 0, 2) - $movieHour == 2){
                    $plans[] = "Dagens planer: $this->nameOfDay ... ". $this->movies[$i]['film']." klockan " . $this->movies[$i]["tid"];
                }
            }
        }
        return $plans;
    }
}