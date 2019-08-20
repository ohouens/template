<?php
class ForumControl{
    public static function read(Post $post, PostManager $postManager, UserManager $userManager, $begin=0, $step=100, $stepIsId=false){
        if(!is_numeric($begin) or !is_numeric($step))
            return;
        $i=0;
        $check = true;
        $verif = true;
        $result = "";
        $list = array_reverse($postManager->getList());
        $final = [];
        foreach($list as $answer){
            if($answer->getType() == Constant::THREAD_ANSWER and $answer->getData()['parent'] == $post->getId())
                array_push($final, $answer);
        }
        while($i<count($final) and $check){
            $inter = $final[$i];
            $id = "";
            if($i == 0)
                $id = "last";
            $first = "";
            if($verif)
                $first = "last";
            if($i == $begin+$step-1)
                $first = "first";
            if($i>=$begin){
                $verif = false;
                $text ='<p class="text alignement">'.nl2br(htmlspecialchars($inter->getField())).'</p>';
                $body = preg_replace("#(https?://[\w?./=&]+)#", '<a href="$1" target="_blank">$1</a>', $text);
                if(isset($inter->getData()['lock'])){
                    self::checkLockActive($inter, $postManager);
                    $body = self::readLock($inter);
                }
                $user = self::getAutor($inter->getUser(), $userManager);
                $result =
                '<div class="'.$first.' answer" id="'.$id.'" cursor="'.$i.'" num="'.$inter->getId().'">
                    <p class="pseudo alignement '.self::color($post, $user).'"><a href="index.php?page='.$user->getId().'">'.$user->getPseudo().'</a></p><!--
                    -->'.$body.'
                    <p class="gris">'.ForumControl::getSeniority($inter).'</p>
                    <hr/>
                </div>'.$result;
            }
            $i++;
            $check = $i<$begin+$step;
            if($stepIsId)
                $check = $inter->getId() != $step;
        }
        return $result;
    }

    public static function checkLockActive(Post $lock, PostManager $manager){
        switch($lock->getData()['lock']){
            case "barrier":
                $date = $lock->getCreation() + intval($lock->getField()) - time();
                if($date <= 0 and $lock->getActive() == 1){
                    $lock->setActive(2);
                    $manager->update($lock);
                }
                $verroux = $lock->getData()["N"] - count($lock->getData()["unlock"]);
                if($verroux <= 0 and $lock->getActive() == 1){
                    $lock->setActive(2);
                    $manager->update($lock);
                }
                break;
            case "vote":
                if($lock->getCreation()+3600*3-time() <= 0 and $lock->getActive() == 1){
                    $lock->setActive(2);
                    $manager->update($lock);
                }
                break;
        }
    }

    public static function readLock(Post $lock){
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
                $view = "Unlocked";
                if($lock->getActive() == 1){
                    $date = $lock->getCreation() + intval($lock->getField()) - time();
                    $verroux = $lock->getData()["N"] - count($lock->getData()["unlock"]);
                    $view = '<p>'.$verroux.' locks</p> <p>'.$date.' secondes</p>';
                }
                $result .=
                '<div class="square barrier '.$active.' '.$voted.'">
                    <div class="center">
                        '.$view.'
                    </div>
                </div>';
                break;
            case "vote":
                $view = "";
                $answer = "";
                $i = 1;
                $attr = "";
                foreach($lock->getData()['answer'] as $key => $value){
                    $attr .= ' a'.$i.'="'.$key.'"';
                    $i++;
                }
                if($lock->getActive()['lock'] == 2 or $voted == "voted"){
                    $view = self::votePercentage($lock);
                    $answer = self::getVoteAnswer($user, $lock);
                }
                $result .=
                '<div class="square vote '.$active.' '.$voted.'" answer="'.$answer.'"'.$attr.'>
                    <div class="center large">
                        <p class="question">'.htmlspecialchars($lock->getField()).'</p>
                        '.$view.'
                    </div>
                </div>';
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
        if($lock->getData()['lock'] == "vote"){
            $answer = $lock->getData()['answer'];
            array_push($answer[$notify], $user->getId());
            $lock->addData(["answer"=>$answer]);
            $manager->update($lock);
        }
        return Constant::ERROR_CODE_OK;
    }

    public static function votePercentage(Post $lock){
        $result = '<div class="aN">';
        $total = count($lock->getData()['unlock']);
        foreach($lock->getData()['answer'] as $key => $list){
            $p = 0;
            if($total > 0)
                $p = round(count($list)*100/$total, 1);
            $result .= '<span class="name">'.$key.':</span><div class="percentage"><div class="grand" style="width:'.$p.'%;"></div></div> <span class="stat">'.$p.'%</span><br><br>';
        }
        $result .= '</div>';
        return $result;
    }

    public static function getVoteAnswer(User $user, Post $lock){
        foreach($lock->getData()['answer'] as $key => $list){
            if(in_array($user->getId(), $list))
                return $key;
        }
        return "";
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
        $temoin = $manager->get($parent);
        switch($var['state']){
            case 0: //message
                if(!preg_match("#^.{1,1000}$#s", $var['answer']))
                    return Constant::ERROR_CODE_THREAD_LENGTH;
                $post->setField($var['answer']);
                break;
            case 1: //lock barrier
                if(!preg_match("/^[0-9]{1,5}$/", $var['length']))
                    return Constant::ERROR_CODE_THREAD_ANSWER;
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
                if(!preg_match("/^[\w]{1,20}$/", $var['a1']))
                    return Constant::ERROR_CODE_THREAD_ANSWER;
                if(!preg_match("/^[\w]{1,20}$/", $var['a2']))
                    return Constant::ERROR_CODE_THREAD_ANSWER;
                $post->setField($var['question']);
                $post->addData(["lock"=>"vote"]);
                $answer = [$var['a1'] => [], $var['a2'] => []];
                if(preg_match("/^[\w]{1,20}$/", $var['a3']))
                    $answer[$var['a3']] = [];
                if(preg_match("/^[\w]{1,20}$/", $var['a4']))
                    $answer[$var['a4']] = [];
                $post->addData(["answer"=>$answer]);
                $post->addData(["unlock"=>[]]);
                break;
        }
        $manager->add($post);
        return ThreadControl::updateList($temoin, $manager);
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
