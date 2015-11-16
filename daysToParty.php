<?php
class daysToParty{
    private $nameOfDay;
    private $movies = array();
    private $barTableTimes = array();

    public function daysToParty($day){
        $this->nameOfDay = $day;
    }
    public function getNameOfDay(){
        return $this->nameOfDay;
    }
    public function addMovie($movie, $time){
        $this->movies[$movie] = $time;
        echo "movie added: $movie, klockan: $time";
    }
    public function addBarTableTimes($time){
        $this->barTableTimes[] = $time;
    }
}