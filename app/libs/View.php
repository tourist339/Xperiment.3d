<?php

class View
{
    protected $view_path;
    protected $view_data;
    protected $view_type;

    public function __construct($view_path,$view_data=[],$view_type)
    {
        $this->view_path=$view_path;
        $this->view_data=$view_data;
        $this->view_type=$view_type;
    }
    public function render($inc_header=HEADER_NORMAL,$inc_footer=FOOTER_NORMAL){


        if($this->view_type=="file") {
            switch ($inc_header){
                case HEADER_NORMAL:
                    require VIEW.'header.phtml';
                    break;
                case HEADER_SCRIPTS_AND_CSS:
                    require VIEW.'headeronlyscripts.phtml';
                    break;
                case HEADER_NONE:
                    break;
            }
            $full_path = VIEW . $this->view_path . ".phtml";
            $this->require_path($full_path);
            if($inc_footer)
                require VIEW.'footer.phtml';
        }
        elseif($this->view_type=="template"){
            $full_path = TEMPLATE .$this->view_path .".html";
            $this->require_path($full_path);
        }




    }
    private function require_path($full_path){
        if(file_exists($full_path)){
            require $full_path;

        }else{
            echo "NOt exists".$full_path;
        }
    }

    /**
     * to convert required php vars to js vars and set them as elements in an object named php_vars
     * @param $php_vars array of php variables to be converted to js vars
     * @param $js_vars array containing the names(string) that js var will be after being converted
     */
    public function convert_php_to_jsvars($php_vars,$js_vars)
    {

            if (count($php_vars) != count($js_vars)) {
                if (ERROR_DEBUG_MODE) {
                    echo "covert_php_to_jsvars functions arguments should have same number of values";

                }else{
                    exit();
                }
            }

            $var_string="";

        for($i=0;$i<count($php_vars);$i++){
            $var_string.=($js_vars[$i]." : ".json_encode($php_vars[$i]) ." ,");
        }

        echo "<script>
                var php_vars=(function(){
                    self={
                        ".$var_string."
                    }
                    return self;
                })();
            </script> ";
    }
}