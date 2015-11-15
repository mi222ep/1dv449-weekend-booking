<?php
$url = "localhost:8080";
$calendarURL = "";
$cinemaURL ="";
$restaurantURL ="";

$curl_scraped_page = curl($url);
$curl_scraped_page = findURLs($curl_scraped_page);
foreach($curl_scraped_page as $page){
    $newpage = $url .$page;
    $test = curl($newpage);
    if (strpos($page,'calendar') !== false) {
        $calendarURL = $newpage;
        echo "<h1>KALENDAR FUNNEN ". $calendarURL."</h1>";
    }
    var_dump($test);
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
function findURLs($data){
    preg_match_all("/<a href=\"([^\"]*)\">(.*)<\/a>/iU",$data, $matches);
    return $matches[1];
}
function printResult(){
    //if det finns minst en kalender - annars error - inga kalendrar hittade
    //if det finns en biosida - annars ingen biosida hittad
    //if det finns minst en passande bio - annars ingen passande film hittad
}
?>