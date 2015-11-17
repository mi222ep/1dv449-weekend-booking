<?php
namespace model;
require_once("/model/calendarModel.php");
class weekendOrganizer{
    private $calendarURL;
    private $cinemaURL;
    private $restaurantURL;
    private $daysToGoParty = array();
    private $cm;

    public function __construct(){
        $this->cm = new calendarModel();
    }
    public function setCalendarURL($url){
        $this->calendarURL = $url;
    }
    public function getCalendarURL(){
        if($this->calendarURL != null){
            return $this->calendarURL;
        }
        else{
            return false;
        }
    }
    public function setCinemaURL($url){
        $this->cinemaURL = $url;
    }
    public function getCinemaURL(){
        return $this->cinemaURL;
    }
    public function setRestaurantURL($url){
        $this->restaurantURL = $url;
    }
    public function getRestaurantURL(){
        return $this->restaurantURL;
    }
    public function addDayToGoParty(daysToParty $day){
        $this->daysToGoParty[] = $day;
    }
    public function getDaysToGoParty(){
        return $this->daysToGoParty;
    }
    public function getWeekendPlans(){
        $plans = array();
        foreach($this->daysToGoParty as $days){
            $plans[] = $days->getTodaysPlans();
        }
        var_dump($plans);
        return $plans;
    }
    public function analyzeCalendars($arr){
        $this->daysToGoParty = $this->cm->analyzeTableFromCalendar($arr);
    }
    public function getTableFromCalendar($arr){
        return $this->cm->findTable($arr);
    }
}
?>