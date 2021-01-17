<?php

     function getProperAddress($addr, $city, $state)
    {
        $result="";
        if(strpos($addr,$city)!==false){
            $result.=$addr;
        }else{
            $result.=($addr." , ".ucfirst($city));

        }
        if(strpos($addr,$state)!==false){
            return $result;
        }else {
            $result .= (" , " . ucfirst($state));
            return $result;
        }
    }

?>