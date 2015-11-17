<?php
namespace model;
class cinemaModel{
    public function getArrayOfMovies($unparsedCinemaPage){
        $DOM = new \DOMDocument;
        $DOM->loadHTML($unparsedCinemaPage);
        $days = $DOM->getElementsByTagName('option');
        $daysForMovie = array();
        for ($i = 0; $i < $days->length; $i++){
            $valueID = $days->item($i)->getAttribute('value');
            $dayToArray = $days->item($i)->nodeValue;
            $daysForMovie[$dayToArray] = $valueID;
        }
        //Remove unneeded data from array
        foreach($daysForMovie as $key =>$value ){
            if($value ==""){
                unset($daysForMovie[$key]);
            }
        }
        return $daysForMovie;
    }
    public function addMoviesToPartydays($daysForMovie, $daysToParty, $cinemaURL){
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
            foreach($daysToParty as $a){
                if($a->getNameOfDay() == $key){
                    foreach($movies as $mKey => $mValue){
                        $movieData = $this->curl($cinemaURL . "/check?day=".$day."&movie=". $mValue);
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
        return $daysForMovie;
    }
}