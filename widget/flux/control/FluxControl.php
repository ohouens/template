<?php
class FluxControl{
    public static function getObject(Post $post, PostManager $manager){

    }

    public static function read(Post $post, PostManager $manager){
        $result = "";
        $list = $manager->getList();
        $final = [];
        foreach($list as $answer){
            if($answer->getType() == Constant::THREAD_ANSWER and $answer->getData()['parent'] == $post->getId())
                array_push($final, $answer);
        }
        foreach($final as $inter){
            $result .=
            '<p class="fluxMessage">'.$inter->getField().'</p>
            <hr class="mark"/>';
        }
        return $result;
    }

    public static function createAnswer(User $user, $answer, Post $parent, PostManager $manager){
        if($user->getId() != $parent->getUser())
            return Constant::ERROR_CODE_THREAD_WRITE_RIGHT;
        if(!preg_match("#^.{1,300}$#", $answer))
            return Constant::ERROR_CODE_THREAD_LENGTH;
        $post = new Post();
        $post->setUser($user->getId());
        $post->setField($answer);
        $post->setType(Constant::THREAD_ANSWER);
        $post->addData(["parent"=>$parent->getId()]);
        $manager->add($post);
        return self::updateSubscriber($post, new PostManager($db), new UserManager($db));
    }

    public static function subscribe(User $user, Post $post, PostManager $manager){
        $id = $user->getId();
        $subscribers = $post->getData()['subscribers'];
        $retour = 0;
        if(in_array($id, $subscribers)){
            $subscribers = array_diff($subscribers, [$id]);
            $retour = 1;
        }else
            array_push($subscribers, $id);
        $post->removeData('subscribers');
        $post->addData(['subscribers' => $subscribers]);
        $manager->update($post);
        $corps = new Widget("","<p>You have subscribe to the thread ".$post->getData()['title']);
        $mail = new WrapperMail($post->getData()['title'], $user, $corps);
        $mail->send();
        return $retour;
    }

    public static function hasSubscribe(User $user, Post $post){
        if(in_array($user->getId(), $post->getData()['subscribers']))
            return 1;
        return 0;
    }

    public static function updateSubscriber(Post $post, PostManager $postManager, UserManager $userManager){
        $corps = new Widget("", "<p>bonjour</p>");
        foreach($post->getData()['subscribers'] as $subscriber){
            $mail = new WrapperMail($post->getData()['title'], $subscriber, $corps);
            $mail->send();
        }
    }
}
