<?php


class e404_controller extends Controller
{
    public const NORMAL_ERR=1;


    public function __construct($errormsg="File Not Found",$type=self::NORMAL_ERR)
    {


        exit();
    }
}