<?php
class FluxControl{
    public static function read(Post $post, PostManager $manager){
        $result = "";
        $final = [];
        $cursor = $post->getData()["head"];
        while($cursor != 0){
            $load = $manager->get($cursor);
            array_push($final, $load);
            $cursor = $load->getData()["next"];
        }
        array_push($final, $post);
        foreach($final as $inter){
            $body = preg_replace("#(https?://[\w?./=&]+)#", '<a href="$1" target="_blank">$1</a>', $inter->getField());
            $text = nl2br(htmlspecialchars($body));
            $result .=
            '<p class="fluxMessage" style="border-left: 2px solid grey; padding-left: 1%; padding-top: 5px; padding-bottom: 15px; margin: 0; margin-left: 4px;">
                '.$body.'
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
        ThreadControl::updateList($parent, $postManager);
        return self::updateSubscriber($parent, $postManager, $userManager);
    }

    public static function subscribe(User $user, Post $post, PostManager $manager){
        if(!$post->getData()['open'])
            return 5;
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
