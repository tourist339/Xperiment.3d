<?php


class Application
{
//    Default controller,method and prams
    protected $controller="home_controller";
    protected $method="index";
    protected $prams=[];

    public function __construct()
    {
        $this->set_controller();
        if(file_exists(CONTROLLER.$this->controller.".php")){
            $currC=new $this->controller;

            if(method_exists($currC,$this->method)){
                try {
                    call_user_func_array([$currC, $this->method], $this->prams);
                }catch (ArgumentCountError $e){
                    new e404_controller();
                }
            }else{
                new e404_controller();
            }
        }else{
            new e404_controller();

        }
    }
    function set_controller(){
        if(isset($_GET['url'])) {
            $url = htmlspecialchars(trim($_GET['url'], '/'));
            $url = explode('/', $url);
            $this->controller = !empty($url[0]) ? $url[0] . "_controller" : $this->controller;

                $this->method = isset($url[1]) ? $url[1] : $this->method;
                unset($url[0], $url[1]);

            $url=array_map(function ($u){return trim($u);},$url);

            $this->prams = $url;
        }

    }


}

?>