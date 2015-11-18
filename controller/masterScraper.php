<?php
namespace controller;

use view\plannerView;

require_once("model/weekendOrganizer.php");
require_once("view/plannerView.php");
class masterScraper{
    private $url;
    private $wo;
    private $view;

    public function __construct(){
        $this->wo = new \model\weekendOrganizer();
        $this->view = new plannerView();
    }
    public function scrapePage(){
        if($this->view->isAdressedTyped()){
            $this->url = $this->view->getURL();
            $mainPage = $this->curl($this->url);
            $mainPage = $this->findURLs($mainPage);

            //Checks found links for key words and sets URLs in wo class
            foreach($mainPage as $page) {
                $newpage = $this->url . $page;
                if (strpos($page, 'calendar') !== false) {
                    $this->wo->setCalendarURL($newpage);
                }
                else if (strpos($page, 'cinema') !== false){
                    $this->wo->setCinemaURL($newpage);
                }
                else if(strpos($page, 'dinner')){
                    $this->wo->setRestaurantURL($newpage);
                }
            }
            //Save url to pages
            foreach($mainPage as $page) {
                $newpage = $this->url . $page;
                if (strpos($page, 'calendar') !== false) {
                    $this->wo->setCalendarURL($newpage);
                }
                else if (strpos($page, 'cinema') !== false){
                    $this->wo->setCinemaURL($newpage);
                }
                else if(strpos($page, 'dinner')){
                    $this->wo->setRestaurantURL($newpage);
                }
            }
            $this->analyzeCalendars();
            $this->analyzeCinema();
            $this->analyzeDinner();
        }
        $this->view->renderNewPlans($this->wo);


    }
    // Finds url's in string. Returns array of url's
    function findURLs($data){
        preg_match_all("/<a href=\"([^\"]*)\">(.*)<\/a>/iU",$data, $matches);
        return $matches[1];
    }
    //retrieves a page. Return string of page
    function curl($url){
        $options = Array(
            CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
            CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
            CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
            CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 120,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_MAXREDIRS => 10, // Setting the maximum number of redirections to follow
            //CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
            CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    //Check for calendarURLs, scrapes the calendars and analyses the content
    function analyzeCalendars(){
        if($this->wo->getCalendarURL()){
            $calendarURL = $this->wo->getCalendarURL();
            $calendarPages = $this->curl($calendarURL);
            $calendarPages = $this->findURLs($calendarPages);
            $calendars  = array();
            foreach($calendarPages as $page){
                $newurl = $calendarURL;
                $newurl .= "/" .$page;
                $calendarPage = $this->curl($newurl);
                $calendarPage=$this->wo->getTableFromCalendar($calendarPage);
                array_push($calendars, $calendarPage);
            }
            $this->wo->analyzeCalendars($calendars);
        }
    }
    //Check for cinemaURL, scrapes the cinema and adds movies to day object
    function analyzeCinema(){
        if($this->wo->getCinemaURL()){
            $cinemaPage = $this->wo->getCinemaURL();
            $unparsedCinemaPage = $this->curl($cinemaPage);
            $moviesAndDays = $this->wo->analyzeCinema($unparsedCinemaPage);
            foreach($moviesAndDays as $key =>$value ){
                if($value ==""){
                    unset($moviesAndDays[$key]);
                }
            }
            $latestValue=0;
            $movies = array();
            //Move movies to another array
            foreach($moviesAndDays as $key => $t){
                if($t > $latestValue){
                    $latestValue=$t;
                }
                else{
                    $latestValue = 999;
                    $movies[$key] = $t;
                    unset($moviesAndDays[$key]);
                }
            }
            foreach($moviesAndDays as $key => $day){
                foreach($this->wo->getDaysToGoParty() as $a){
                    if($a->getNameOfDay() == $key){
                        foreach($movies as $mKey => $mValue){
                            $movieData = $this->curl($this->wo->getCinemaURL() . "/check?day=".$day."&movie=". $mValue);
                            $moviesDecoded = (array) json_decode($movieData, true);
                            for($i =0; $i<sizeof($moviesDecoded); $i++){
                                if($moviesDecoded[$i]["status"] == 1){
                                    $a->addMovie($mKey, $moviesDecoded[$i]["time"]);
                                }
                            }
                        }
                    }
                }
            }
        }
        else{
        }

    }
    //Check for dinnerURL, scrapes the dinner page, analyzes and add aviable times to choosen day(s)
    function analyzeDinner(){
        if($this->wo->getRestaurantURL()){
            $restaurantpage = $this->curl($this->wo->getRestaurantURL());
            $DOM = new \DOMDocument;
            libxml_use_internal_errors(true);
            $DOM->loadHTML($restaurantpage);
            libxml_use_internal_errors(false);
            $span = $DOM->getElementsByTagName('input');
            foreach($span as $s){
                    foreach($this->wo->getDaysToGoParty() as $day){
                        $shortDayName = $day->getShortName();
                        if(preg_match("/".$shortDayName ."/", $s->getAttribute("value"))){
                            $time = str_replace($shortDayName, "", $s->getAttribute("value"));
                            $day->addBarTableTimes($time);
                    }}

        }
    }
}}
?>