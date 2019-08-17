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
            $body ='<p class="text alignement">'.nl2br($inter->getField()).'</p>';
            if(isset($inter->getData()['lock']))
                $body = self::readLock($inter, $postManager);
            $user = self::getAutor($inter->getUser(), $userManager);
            $result .=
            '<div class="answer" id="'.$id.'">
                <p class="pseudo alignement '.self::color($post, $user).'">'.$user->getPseudo().'</p><!--
                -->'.$body.'
                <p class="gris">'.ForumControl::getSeniority($inter).'</p>
                <hr/>
            </div>';
        }
        return $result;
    }

    public static function readLock(Post $lock, PostManager $manager){
        global $user;
        $result = '<div class="lock">';
        $active = "active";
        if($lock->getActive() == 2)
            $active = "";
        $voted = "";
        if(in_array($user->getId(), $lock->getData()['unlock']))
            $voted = "voted";
        switch($lock->getData()['lock']){
            case "barrier":
                $date = $lock->getCreation() + intval($lock->getField()) - time();
                if($date <= 0){
                    $date = "0";
                    if($lock->getActive() == 1){
                        $lock->setActive(2);
                        $manager->update($lock);
                    }
                }
                $verroux = $lock->getData()["N"] - count($lock->getData()["unlock"]);
                if($verroux <= 0 and $lock->getActive() == 1){
                    $lock->setActive(2);
                    $manager->update($lock);
                }
                $view = '<p>'.$verroux.' locks</p> <p>'.$date.' secondes</p>';
                if($lock->getActive() == 2)
                    $view = "Unlocked";
                $result .=
                '<div class="square barrier '.$active.' '.$voted.'">
                    <div class="center">
                        '.$view.'
                    </div>
                </div>';
                break;
            case "vote":
                break;
        }
        $result .= '</div>';
        return $result;
    }

    public static function getLastLock(Post $post, PostManager $manager){
        $list = array_reverse($manager->getList());
        foreach($list as $inter){
            if($inter->getType() == Constant::THREAD_ANSWER){
                if($inter->getData()['parent'] == $post->getId())
                    return $inter;
            }
        }
        return null;
    }

    public static function notifyLock(User $user, Post $post, $notify, PostManager $manager){
        $lock = self::getLastLock($post, $manager);
        if($lock->getActive() == 2)
            return 443;
        if(in_array($user->getId(), $lock->getData()['unlock']))
            return 444;
        $unlock = $lock->getData()['unlock'];
        array_push($unlock, $user->getId());
        $lock->addData(['unlock'=>$unlock]);
        $manager->update($lock);
        return Constant::ERROR_CODE_OK;
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
                $temoin = $manager->get($parent);
                if(count($temoin->getData()['followers']) <= 0)
                    return 447;
                $post->setField($var['length']);
                $post->addData(["lock"=>"barrier"]);
                $post->addData(["N"=>count($temoin->getData()['followers'])]);
                $post->addData(["unlock"=>[]]);
                break;
            case 2: //lock vote
                if(!preg_match("/^.{1,100}$/", $var['question']))
                    return Constant::ERROR_CODE_THREAD_LENGTH;
                if(!preg_match("/^.{1,20}$/", $var['a1']))
                    return Constant::ERROR_CODE_THREAD_ANSWER;
                if(!preg_match("/^.{1,20}$/", $var['a2']))
                    return Constant::ERROR_CODE_THREAD_ANSWER;
                $post->setField($var['question']);
                $post->addData(["lock"=>"vote"]);
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
