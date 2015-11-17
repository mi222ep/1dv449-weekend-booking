<?php
namespace view;
class plannerView{
    public function __construct()
    {
    }
    public function renderStart(){

    }
    public function renderNewPlans($plans){
        $this->renderHeader();
        foreach($plans as $plan){
            foreach($plan as $p){
                echo $p->day;
                echo $p->time;
                echo $p->movie;
                echo "<br>";
            }
        }
        $this->renderFooter();
    }
    private function renderHeader(){

    }
    private function renderFooter(){

    }
}