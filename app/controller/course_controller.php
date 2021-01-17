<?php


class course_controller extends Controller
{
    public function __construct()
    {

    }

    public function index(){
    }
    public function l($subject_name,$topic_name="",$subtopic_code=""){

        $subs_data=Subject::getSubjects();
        $found =false;
        $sub_topics=[];
        foreach ($subs_data as $sub){
            if($sub["name"]==$subject_name){
                $found=true;
                $sub_topics=$sub["topics"];
            };
        }
        if (!$found){
            new e404_controller();
        }
        if (empty($topic_name)&& empty($subtopic_code)) {
            $cView = $this->createView('subject/index', ["title" => $subject_name,
                    "scripts" => [MAIN_SCRIPTS],
                    "stylesheets" => [MAIN_CSS, "style.css"],
                    "data" => $sub_topics,
                    "navbar" => MAIN_NAVBAR]
            );
            $cView->render();

        }else  { //when subject name and topic name are both given

            $topic=[];
            foreach ($sub_topics as $t) {
                if (strtolower($t["name"]) == $topic_name) {
                    $topic=$t;
                }
            }
            if ($topic==[]){
                new e404_controller();
            }
            if (empty($subtopic_code)){
                $active_subtopic=$topic["default_subtopic"];
            }else{
                $active_subtopic=$subtopic_code;

            }
            $cView = $this->createView('/subject/topic/index', ["title" => $subject_name,
                    "scripts" => [MAIN_SCRIPTS,"custom_viewer.js"],
                    "stylesheets" => [MAIN_CSS, "content_page/page.css"],
                    "data" => $topic,
                    "active_subtopic"=>$active_subtopic,
                    "url"=>"/course/l/".$subject_name."/".$topic_name."/",
                    "navbar" => MAIN_NAVBAR]
            );
            $cView->render();




        }

    }

    private function getDefaultData(){

    }
}