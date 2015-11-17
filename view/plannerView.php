<?php
namespace view;
class plannerView{

    private static $url = "url";
    private static $textEntered = "entered";
    public function render(){

    }
    public function renderNewPlans(\model\weekendOrganizer $organizer){
        $this->renderHeader();
        echo $this->generateTextEnter();
        $plans = $organizer->getWeekendPlans();
        if($this->isAdressedTyped()){
            foreach($plans as $plan){
                foreach($plan as $p){
                    echo $p->day;
                    echo $p->time;
                    echo $p->movie;
                    echo "<br>";
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
					<input type="submit" name="' . self::$textEntered . '" value="login" />
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