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
    //Scrapes main page for URLs, set URLs in wo class
    public function scrapePage(){
        if($this->view->isAdressedTyped()){
            $this->url = $this->view->getURL();
            $curl_scraped_page = $this->curl($this->url);
            $curl_scraped_page = $this->findURLs($curl_scraped_page);

            //Checks found links for key words and sets URLs in wo class
            foreach($curl_scraped_page as $page) {
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
            foreach($curl_scraped_page as $page) {
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
    function findURLs($data){
        preg_match_all("/<a href=\"([^\"]*)\">(.*)<\/a>/iU",$data, $matches);
        return $matches[1];
    }
    function curl($url){
        $options = Array(
            CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
            CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
            CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
            CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 120,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_MAXREDIRS => 10, // Setting the maximum number of redirections to follow
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
            CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
        );

        $ch = curl_init();  // Initialising cURL
        curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL
        return $data;   // Returning the data from the function
    }
    function analyzeCalendars(){
        if($this->wo->getCalendarURL()){
            $calendarURL = $this->wo->getCalendarURL();
            $calendarPages = $this->curl($calendarURL);
            $calendarPages = $this->findURLs($calendarPages);
            $calendars  = array();
            foreach($calendarPages as $page){
                $newurl = $calendarURL;
                $newurl .= "/" .$page;
                $test = $this->curl($newurl);
                $test=$this->wo->getTableFromCalendar($test);
                //$test = $this->findTable($test);
                array_push($calendars, $test);
            }
            $this->wo->analyzeCalendars($calendars);
        }
    }
    function analyzeCinema(){
        if($this->wo->getCinemaURL()){
            $cinemaPage = $this->wo->getCinemaURL();
            $unparsedCinemaPage = $this->curl($cinemaPage);
            $daysForMovie = $this->wo->analyzeCinema($unparsedCinemaPage);
            foreach($daysForMovie as $key =>$value ){
                if($value ==""){
                    unset($daysForMovie[$key]);
                }
            }
            $latestValue=0;
            $movies = array();
            //Move movies to another array
            foreach($daysForMovie as $key => $t){
                if($t > $latestValue){
                    $latestValue=$t;
                }
                else{
                    $latestValue = 999;
                    $movies[$key] = $t;
                    unset($daysForMovie[$key]);
                }
            }
            foreach($daysForMovie as $key => $day){
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
                        $temp = $day->getShortName();
                        if(preg_match("/".$temp ."/", $s->getAttribute("value"))){
                            $time = str_replace($temp, "", $s->getAttribute("value"));
                            $day->addBarTableTimes($time);
                    }}

        }
    }
}}
?>