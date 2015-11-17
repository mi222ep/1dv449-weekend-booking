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
        var_dump($plans);
        foreach($plans as $plan){
            $plan->day;
            echo "<br>";
        }
        $this->renderFooter();
    }
    private function renderHeader(){

    }
    private function renderFooter(){

    }
}