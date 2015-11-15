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
            if (strpos($page, 'calendar') !== false) {
                $this->wo->setCalendarURL($newpage);
            }
        }
        if($this->wo->getCalendarURL()){
            $calendarPages = $this->curl($this->wo->getCalendarURL());
            $calendarPages = $this->findURLs($calendarPages);
            foreach($calendarPages as $page){
                $newurl = $this->wo->getCalendarURL();
                $newurl .= "/" .$page;
                $test = $this->curl($newurl);
                echo $test;
            }
            echo "Kalendersida funnen";
            $this->getTable();
        }
        else{
            echo "Ingen kalender funnen";
        }
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
    function getTable(){
        $days = array(
            array("friday" => true,
            "saturday" => false,
            "sunday" => true),
            array("friday" => false,
                "saturday" => false,
                "sunday" => true),
            array("friday" => false,
                "saturday" => false,
                "sunday" => true),
    );
        $saturdayOK = true;
        $fridayOK = true;
        $sundayOK = true;

        for( $i =0; $i<sizeof($days); $i++){
            if($days[$i]["friday"] == false){
                $fridayOK = false;
            }
            if($days[$i]["saturday"] == false){
                $saturdayOK = false;
            }
            if($days[$i]["sunday"] == false){
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
}
?>