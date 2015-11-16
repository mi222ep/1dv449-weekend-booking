<?php
include("weekendOrganizer");
class masterScraper{
    private $url = "localhost:8080";
    private $wo;

    public function masterScraper(){
    $this->wo = new weekendOrganizer();
    }
    public function scrapePage(){
        $curl_scraped_page = $this->curl($this->url);
        $curl_scraped_page = $this->findURLs($curl_scraped_page);

        foreach($curl_scraped_page as $page) {
            $newpage = $this->url . $page;
            //Set calendar page
            if (strpos($page, 'calendar') !== false) {
                $this->wo->setCalendarURL($newpage);
            }
            else if (strpos($page, 'cinema') !== false){
                $this->wo->setCinemaURL($newpage);
            }
            else if(strpos($page, 'dinner')){

            }
        }
        if($this->wo->getCalendarURL()){
            $calendarPages = $this->curl($this->wo->getCalendarURL());
            $calendarPages = $this->findURLs($calendarPages);
            $calendars  = array();
            foreach($calendarPages as $page){
                $newurl = $this->wo->getCalendarURL();
                $newurl .= "/" .$page;
                $test = $this->curl($newurl);
                $test = $this->findTable($test);
                array_push($calendars, $test);
            }
            echo "Kalendersida funnen";
            $this->analyzeTableFromCalendar($calendars);
        }
        else{
            echo "Ingen kalender funnen";
        }
    }
    function findURLs($data){
        preg_match_all("/<a href=\"([^\"]*)\">(.*)<\/a>/iU",$data, $matches);
        return $matches[1];
    }
    function findTable($data){
        $DOM = new DOMDocument;
        $DOM->loadHTML($data);
        $days = $DOM->getElementsByTagName('th');
        $items = $DOM->getElementsByTagName('td');
        $test = array();
        for ($i = 0; $i < $items->length; $i++){
            $dayToArray = $days->item($i)->nodeValue;
            $okOrNotToArray = $items->item($i)->nodeValue;
            if(preg_match("/ok/i", $okOrNotToArray)){
                $okOrNotToArray =true;
            }
            else{
                $okOrNotToArray = false;
            }
            $test[$dayToArray] = $okOrNotToArray;
        }
        return $test;
    }
    function analyzeTableFromCalendar($arr){

        $saturdayOK = true;
        $fridayOK = true;
        $sundayOK = true;

        for( $i =0; $i<sizeof($arr); $i++){
            if($arr[$i]["Friday"] == false){
                $fridayOK = false;
            }
            if($arr[$i]["Saturday"] == false){
                $saturdayOK = false;
            }
            if($arr[$i]["Sunday"] == false){
                $sundayOK = false;
            }
        }
        if($fridayOK){
            echo "Friday OK";
        }
        if($saturdayOK){
            echo "saturday OK";
        }
        if($sundayOK){
            echo "sunday ok";
        }
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
}
?>