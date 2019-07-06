<?php
class ForumControl{
    public static function getAutor($id, Manager $manager){
        return $manager->get($id);
    }

    public static function getSeniority(Post $post){
        $d1 = DateTime::createFromFormat('U',$post->getCreation());
        $d2 = new DateTime("now");
        $interval = $d1->diff($d2);
        if($interval->y > 1)
            return $interval->format("%y years ago");
        elseif($interval->m > 1)
            return $interval->format("%m months ago");
        elseif($interval->d > 1)
            return $interval->format("%d days ago");
        elseif($interval->i > 1)
            return $interval->format("%i minutes ago");
        else
            return "a moment moment ago";
    }

    public static function getObject(Post $post, Manager $manager){

    }
}
