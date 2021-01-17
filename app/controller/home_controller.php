<?php


class home_controller extends Controller
{
    public function __construct()
    {

    }
    public function index(){
        //include title ,scripts with .js extension and stylesheets with .css extension in the data array
        //navbars are in template/navbars dir , create new one or choose any of the pre existing
        $cView=$this->createView('/home/index',["title"=>"LyfLy",
                "scripts" => [MAIN_SCRIPTS,"script.js"],
                "stylesheets" => [MAIN_CSS,"style.css"],
                "navbar" => MAIN_NAVBAR]
        );
        $cView->render();

    }

    private function getDefaultData(){

    }
}