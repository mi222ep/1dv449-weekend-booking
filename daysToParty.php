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
        $this->movies[$movie] = $time;
    }
    public function addBarTableTimes($time){
        $this->barTableTimes[] = $time;
    }
    public function getTodaysPlans(){
        $plans = array();
        var_dump($this->movies);
        foreach($this->movies as $movieName=>$movieTime){
            $movieHour = substr($movieTime, 0, 2);
            foreach($this->barTableTimes as $time){
                if(substr($time, 0, 2) - $movieHour == 2){
                    $plans[] = "Dagens planer: $this->nameOfDay ... $movieName klockan $movieTime ";
                }
            }
        }
        return $plans;
    }
}