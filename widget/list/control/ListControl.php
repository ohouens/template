<?php
class ListControl{
    public static function read(Post $post, PostManager $manager){
        $result = "";
        foreach($post->getData()['list'] as $num){
            $thread = $manager->get($num);
            $result .= ContainThreadControl::construct($thread, $manager);
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
                switch($thread->getType()){
                    case Constant::THREAD_FORUM://--active/thread/operation/action/forum/subscribe|unsubscribe--//
                        if(ForumControl::hasSubscribe($user, $thread) == 0)
                            ForumControl::subscribe($user, $thread, $manager);
                        break;
                    case Constant::THREAD_FLUX://--active/thread/operation/action/flux-
                        FluxControl::subscribe($user, $thread, $manager);
                        break;
                    case Constant::THREAD_TICKETING:
                        TicketingControl::subscribe($user, $thread, $manager);
                        break;
                    case Constant::THREAD_LIST:
                        if(ThreadControl::hasSubscribe(ThreadControl::getInfluence($thread), $user, $thread) == 0)
                            ListControl::subscribe($user, $thread, $manager, true);
                        break;
                    default:
                        break;
                }
            }
        }
        return $state;
    }

    public static function unsubscribe(User $user, Post $post, PostManager $manager, $all){
        $state = ThreadControl::unsubscribe(ThreadControl::getInfluence($post), $user, $post, $manager);
        if($state == 0 and $all){
            foreach($post->getData()['list'] as $num){
                $thread = $manager->get($num);
                $user->addData(["pass"=>$thread->getData()['keys'][$user->getId()]]);
                switch($thread->getType()){
                    case Constant::THREAD_FORUM://--active/thread/operation/action/forum/subscribe|unsubscribe--//
                        if(ForumControl::hasSubscribe($user, $thread) == 1)
                            ForumControl::subscribe($user, $thread, $manager);
                        break;
                    case Constant::THREAD_FLUX://--active/thread/operation/action/flux-
                        FluxControl::unsubscribe($user, $thread, $manager);
                        break;
                    case Constant::THREAD_TICKETING:
                        TicketingControl::unsubscribe($user, $thread, $manager);
                        break;
                    case Constant::THREAD_LIST:
                        if(ThreadControl::hasSubscribe(ThreadControl::getInfluence($thread), $user, $thread) == 1)
                            ListControl::unsubscribe($user, $thread, $manager, true);
                        break;
                    default:
                        break;
                }
            }
        }
        return $state;
    }
}
