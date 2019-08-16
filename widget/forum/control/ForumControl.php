<?php
class ForumControl{
    public static function getObject(User $user, Post $post, Manager $manager){

    }

    public static function read(Post $post, PostManager $postManager, UserManager $userManager){
        $result = "";
        $list = $postManager->getList();
        $final = [];
        foreach($list as $answer){
            if($answer->getType() == Constant::THREAD_ANSWER and $answer->getData()['parent'] == $post->getId())
                array_push($final, $answer);
        }
        for($i=0; $i<count($final); $i++){
            $inter = $final[$i];
            $id = $inter->getId();
            if($i == count($final)-1)
                $id = "last";
            $user = self::getAutor($inter->getUser(), $userManager);
            $result .=
            '<div class="answer" id="'.$id.'">
                <p class="pseudo alignement '.self::color($post, $user).'">'.$user->getPseudo().'</p><!--
                --><p class="text alignement">'.nl2br($inter->getField()).'</p>
                <p class="gris">'.ForumControl::getSeniority($inter).'</p>
                <hr/>
            </div>';
        }
        return $result;
    }

    public static function getAutor($id, Manager $manager){
        return $manager->get($id);
    }

    public static function color(Post $post, User $user){
        if($user->getId() == $post->getUser())
            return 'creator';
        if(in_array($user->getId(), $post->getData()['writers']))
            return 'writer';
        if(in_array($user->getId(), $post->getData()['followers']))
            return 'follower';
        return '';
    }

    public static function getSeniority(Post $post){
        $d1 = DateTime::createFromFormat('U',$post->getCreation());
        $d2 = new DateTime("now");
        $interval = $d1->diff($d2);
        if($interval->y >= 1)
            return $interval->format("%y years ago");
        elseif($interval->m >= 1)
            return $interval->format("%m months ago");
        elseif($interval->d >= 1)
            return $interval->format("%d days ago");
        elseif($interval->h >= 1)
            return $interval->format("%h hours ago");
        elseif($interval->i >= 1)
            return $interval->format("%i minutes ago");
        else
            return "a moment moment ago";
    }

    public static function createAnswer(User $user, $var, $parent, PostManager $manager){
        $post = new Post();
        $post->setUser($user->getId());
        $post->setType(Constant::THREAD_ANSWER);
        $post->addData(["parent"=>$parent]);
        switch($var['state']){
            case 0: //message
                if(!preg_match("#^.{1,1000}$#s", $var['answer']))
                    return Constant::ERROR_CODE_THREAD_LENGTH;
                $post->setField($var['answer']);
                break;
            case 1: //lock barrier
                if(!preg_match("/^[0-9]{1,5}$/", $var['length']))
                    return Constant::ERROR_CODE_THREAD_ANSWER;
                $post->setField($var['length']);
                $post->addData(["lock"=>"barrier"]);
                break;
            case 2: //lock vote
                if(!preg_match("/^.{1,100}$/", $var['question']))
                    return Constant::ERROR_CODE_THREAD_LENGTH;
                if(!preg_match("/^.{1,20}$/", $var['a1']))
                    return Constant::ERROR_CODE_THREAD_ANSWER;
                if(!preg_match("/^.{1,20}$/", $var['a2']))
                    return Constant::ERROR_CODE_THREAD_ANSWER;
                $post->setField($var['question']);
                $post->addData(["a1"=>$var["a1"], "a2"=>$var["a2"]]);
                if(preg_match("/^.{1,20}$/", $var['a3']))
                    $post->addData(["a3"=>$var["a3"]]);
                if(preg_match("/^.{1,20}$/", $var['a4']))
                    $post->addData(["a4"=>$var["a4"]]);
                break;
        }
        $manager->add($post);
        return 0;
    }

    public static function subscribe(User $user, Post $post, PostManager $manager){
        $id = $user->getId();
        $followers = $post->getData()['followers'];
        $retour = 0;
        if(in_array($id, $followers)){
            $followers = array_diff($followers, [$id]);
            $retour = 1;
        }else
            array_push($followers, $id);
        $post->removeData('followers');
        $post->addData(['followers' => $followers]);
        $manager->update($post);
        return $retour;
    }

    public static function hasSubscribe(User $user, Post $post){
        if(in_array($user->getId(), $post->getData()['followers']))
            return 1;
        return 0;
    }
}
