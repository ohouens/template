<?php
class FluxControl{
    public static function read(Post $post, PostManager $manager){
        $result = "";
        $list = $manager->getList();
        $final = [];
        foreach(array_reverse($list) as $answer){
            if($answer->getType() == Constant::THREAD_ANSWER and $answer->getData()['parent'] == $post->getId())
                array_push($final, $answer);
        }
        array_push($final, $post);
        foreach($final as $inter){
            $result .=
            '<p class="fluxMessage" style="border-left: 2px solid grey; padding-left: 1%; padding-top: 5px; padding-bottom: 15px; margin: 0; margin-left: 4px;">
                '.nl2br($inter->getField()).'
            </p>
            <hr class="mark" style="width: 8px; height: 8px; border-radius: 50%; background: grey; margin: 0; display: inline-block;"/>';
        }
        return $result;
    }

    public static function createAnswer(User $user, $answer, Post $parent, PostManager $postManager, UserManager $userManager){
        if($user->getId() != $parent->getUser())
            return Constant::ERROR_CODE_THREAD_WRITE_RIGHT;
        if(!preg_match("#^.{1,500}$#s", $answer))
            return Constant::ERROR_CODE_THREAD_LENGTH;
        $post = new Post();
        $post->setUser($user->getId());
        $post->setField($answer);
        $post->setType(Constant::THREAD_ANSWER);
        $post->addData(["parent"=>$parent->getId()]);
        $postManager->add($post);
        return self::updateSubscriber($parent, $postManager, $userManager);
    }

    public static function subscribe(User $user, Post $post, PostManager $manager){
        $state = ThreadControl::subscribe('subscribers', $user, $post, $manager);
        if($state == 0){
            $corps = new SubscribeMailWidget($user, $post, $manager);
            $mail = new WrapperMail($post->getData()['title'], $user, $corps);
            $mail->send();
        }
        return $state;
    }

    public static function unsubscribe(User $user, Post $post, PostManager $manager){
        $state = ThreadControl::unsubscribe('subscribers', $user, $post, $manager);
        if($state == 0){

        }
        return $state;
    }

    public static function hasSubscribe(User $user, Post $post){
        if(in_array($user->getId(), $post->getData()['subscribers']))
            return 1;
        return 0;
    }

    public static function updateSubscriber(Post $post, PostManager $postManager, UserManager $userManager){
        foreach($post->getData()['subscribers'] as $subscriber){
            $user = ThreadControl::getUser($subscriber, $userManager);
            $corps = new HistoryMailWidget($user, $post, $postManager);
            $mail = new WrapperMail($post->getData()['title'], $user, $corps);
            $mail->send();
        }
        return 0;
    }
}
