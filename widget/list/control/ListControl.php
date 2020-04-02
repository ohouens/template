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

    public static function subscribe(User $user, Post $post, PostManager $manager, $all){
        if(!$post->getData()['open'])
            return 5;
        $state = ThreadControl::subscribe(ThreadControl::getInfluence($post), $user, $post, $manager);
        if($state == 0 and $all){
            foreach($post->getData()['list'] as $num){
                $thread = $manager->get($num);
                $content = ThreadControl::getInfluence($thread);
                ThreadControl::subscribe($content, $user, $post, $manager);
            }
        }
        return $state;
    }

    public static function unsubscribe(User $user, Post $post, PostManager $manager, $all){
        $state = ThreadControl::unsubscribe(ThreadControl::getInfluence($post), $user, $post, $manager);
        if($state == 0 and $all){
            foreach($post->getData()['list'] as $num){
                $thread = $manager->get($num);
                $content = ThreadControl::getInfluence($thread);
                ThreadControl::unsubscribe($content, $user, $post, $manager);
            }
        }
        return $state;
    }
}
