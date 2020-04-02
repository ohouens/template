<?php
class ListControl{
    public static function read(Post $post, PostManager $manager){
        $result = "";
        foreach($post->getData()['list'] as $num){
            $thread = $manager->get($num);
            $result .= ContainThreadControl::construct($thread);
        }
        return $result;
    }
}
