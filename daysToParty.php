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
}