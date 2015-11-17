<?php
namespace view;
class plannerView{

    private static $url = "url";
    private static $textEntered = "entered";

    public function renderNewPlans(\model\weekendOrganizer $organizer){
        $this->renderHeader();
        echo $this->generateTextEnter();
        $plans = $organizer->getWeekendPlans();
        if($this->isAdressedTyped()){
            if($plans == null){
                if($organizer->wasURLsFound()){
                    Echo "Alla sidor hittades, men alla krav kan ej uppfyllas.";
                }
                else{
                    Echo "Hittade inte alla sidor. Har du angett korrekt adress?";
                }
            }
            else{
                Echo "<h2>Filmerna nedan passar alla. Lediga bord finns hos Zeke efter filmen.</h2>";
                foreach($plans as $plan){
                    foreach($plan as $p){
                        echo "<b>$p->day</b> klockan $p->time visas filmen $p->movie <br>";
                    }
                }
            }
        }
        $this->renderFooter();
    }
    private function renderHeader(){
        echo"<!DOCTYPE html>
<html>
<head>
<link href='style/style.css' rel =stylesheet type='text/css'>
<meta http-equiv='content-type' content='text/html' charset='utf-8' />
	<title>Weekend planner</title>
</head>
<body>
 <div id='contentwrapper'>";
    }
    private function renderFooter(){
        echo" </div>
 </body>
 </html>";
    }
    private function generateTextEnter(){
        return '
			<form method="post" >
				<fieldset>
					<legend>Skriv in adress till samlingssidan med kalendrar, bio och restaurang</legend>

					<label for="' . self::$url . '">URL :</label>
					<input type="text" id="' . self::$url . '" name="' . self::$url . '" />
					<input type="submit" name="' . self::$textEntered . '" value="planera" />
				</fieldset>
			</form>
		';
    }
    public function isAdressedTyped(){
        return ISSET($_POST[self::$textEntered]);
    }
    public function getURL(){
        return $_POST[self::$url];
    }
}