<?php
namespace model;
class confirmedPlan{
    public $day;
    public $time;
    public $movie;

    public function __construct($day, $time, $movie){
        $this->day = $day;
        $this->time = $time;
        $this->movie = $movie;
    }
    public function getDay(){
        return $this->day;
    }
}