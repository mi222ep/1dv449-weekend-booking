<?php
namespace model;
require_once("model/calendarModel.php");
require_once("model/cinemaModel.php");
class weekendOrganizer{
    private $calendarURL;
    private $cinemaURL;
    private $restaurantURL;
    private $daysToGoParty = array();
    private $calendarModel;
    private $cinemaModel;

    public function __construct(){
        $this->calendarModel = new calendarModel();
        $this->cinemaModel = new cinemaModel();
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
        return $plans;
    }
    public function analyzeCinema($unparsedCinemaPage){
        return $this->cinemaModel->getArrayOfMovies($unparsedCinemaPage);
    }
    public function analyzeCalendars($arr){
        $this->daysToGoParty = $this->calendarModel->analyzeTableFromCalendar($arr);
    }
    public function getTableFromCalendar($arr){
        return $this->calendarModel->findTable($arr);
    }
    public function wasURLsFound(){
        if($this->calendarURL != null && $this->cinemaURL != null && $this->restaurantURL !=null){
            return true;
        }
        else{
            return false;
        }
    }
}
?>