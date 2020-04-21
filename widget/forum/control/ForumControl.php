<?php
class ForumControl{
    public static function read(Post $post, PostManager $postManager, UserManager $userManager, $begin=0, $step=100, $stepIsId=false){
        if(!is_numeric($begin) or !is_numeric($step))
            return;
        $i=0;
        $check = true;
        $verif = true;
        $result = "";
        $cursor = $post->getData()["head"];
        try{
            while($cursor != 0 and $check){
                $inter = $postManager->get($cursor);
                if(is_int($inter))
                    throw new Exception("Error Processing linked list Request");
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
                    $body = preg_replace("#(https?://[\w?./=~&_-]+)#", '<a href="$1" target="_blank">$1</a>', $text);
                    $body = preg_replace("#    #", '<span class="indent"></span>', $body);
                    $body = preg_replace("#\[(.*)\]#", '<span class="crochet">[</span>$1<span class="crochet">]</span>', $body);
                    $body = preg_replace("/(#[a-zàâçéèêëîïôûùüÿñæœ]*)/", '<span class="hashtag">$1</span>', $body);
                    $body = preg_replace("#{(.*)}#", '<span class="accolade">{</span>$1<span class="accolade">}</span>', $body);
                    $body = preg_replace("#::(.*)::#", '::<span class="warning">$1</span>::', $body);
                    $body = preg_replace("#(@([a-z0-9_]{0,20}))#", '<a href="index.php?page=$2" class="at" style="color: #ff009d;">$1</a>', $body);
                    $body = preg_replace("#([\w]{40})#", '<a href="index.php?thread=$1" class="to" style="color: #abd6f3;">$1</a>', $body);
                    $body = preg_replace("/(~[$])/", '<span class="unix" style="color: #007070;">$1</span>', $body);
                    if(isset($inter->getData()['lock'])){
                        self::checkLockActive($inter, $postManager);
                        $body = self::readLock($inter);
                    }
                    try{
                        $user = self::getAutor($inter->getUser(), $userManager);
                        $account = '<p class="pseudo alignement '.self::color($post, $user).'"><a href="index.php?page='.$user->getId().'">'.$user->getPseudo().'</a></p>';
                    }catch(Exception $e){
                        $account = '<p class="pseudo alignement">Account deleted</p>';
                    }
                        $result =
                        '<div class="'.$first.' answer" id="'.$id.'" cursor="'.$i.'" num="'.$inter->getId().'">
                            '.$account.'<!--
                            -->'.$body.'
                            <p class="gris">'.ForumControl::getSeniority($inter).'</p>
                            <hr/>
                        </div>'.$result;
                }
                $i++;
                $check = $i<$begin+$step;
                if($stepIsId)
                    $check = $inter->getId() != $step;
                $cursor = $inter->getData()["next"];
            }
            return $result;
        }catch(Exception $e){
            echo $e->getMessage();
            ThreadControl::initList($post, $postManager);
        }
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
                if($lock->getActive() == 2 or $voted == "voted"){
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
                if($inter->getData()['parent'] == $post->getId() and isset($inter->getData()['lock']))
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
        $result =
        '<div class="aN">';
        $total = count($lock->getData()['unlock']);
        foreach($lock->getData()['answer'] as $key => $list){
            $p = 0;
            if($total > 0)
                $p = round(count($list)*100/$total, 1);
            $result .= '<span class="name">'.$key.':</span><div class="percentage"><div class="grand" style="width:'.$p.'%;"></div></div> <span class="stat">'.$p.'%</span><br><br>';
        }
        $result .=
        '   <p class="nbVote">'.$total.' votes</p>
        </div>';
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
        $autor = $manager->get($id);
        if(is_int($autor))
            throw new Exception("Error autor doesn't exist");
        return $autor;
    }

    public static function color(Post $post, User $user){
        if($user->getId() == $post->getUser())
            return 'creator';
        if(in_array($user->getPseudo(), $post->getData()['writers']))
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

    public static function createAnswer(User $user, $var, $parent, PostManager $pm, UserManager $um){
        $temoin = $pm->get($parent);
        if(!ThreadControl::checkMode($user, $temoin, "write"))
            return 44;
        $lock = self::getLastLock($temoin, $pm);
        if($lock != NULL and $lock->getActive() == 1)
            return Constant::ERROR_CODE_THREAD_ANSWER;
        $post = new Post();
        $post->setUser($user->getId());
        $post->setType(Constant::THREAD_ANSWER);
        $post->addData(["parent"=>$parent]);
        switch($var['state']){
            case 0: //message
                if(!preg_match("#^.{1,1000}$#s", $var['answer']))
                    return Constant::ERROR_CODE_THREAD_LENGTH;
                $post->setField($var['answer']);
                if(self::isOrder($var["answer"])){
                    if($temoin->getData()['execute'] == 0)
                        return 0;
                    $result = self::createOrder($user, $var["answer"], $temoin, $pm, $um);
                    if(is_int($result))
                        return $result;
                    $post->addData(["order"=>explode(" ", $result)[1]]);
                    $post->setField(explode(":", $result)[1]);
                }
                break;
            case 1: //lock barrier
                if($user->getId() != $temoin->getUser() and !in_array($user->getPseudo(), $temoin->getData()['writers']))
                    return 444;
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
                if($user->getId() != $temoin->getUser() and !in_array($user->getPseudo(), $temoin->getData()['writers']))
                    return 444;
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
        $pm->add($post);
        return ThreadControl::updateList($temoin, $pm);
    }

    public static function isOrder($order){
        if(!preg_match("/^\~[$] /", $order))
            return false;
        return true;
    }

    public static function createOrder(User $user, $answer, Post $parent, PostManager $pm, UserManager $um){
        global $hash;
        $order = explode(" ", $answer);
        $result = [];
        array_push($result, "@".$user->getPseudo().":~$");
        array_push($result, $order[1]);
        switch ($order[1]){
            case 'declare':
                if(!preg_match("/^.{1,240}$/", implode(" ", array_slice($order, 2))))
                    return 44;
                array_push($result, '"'.implode(" ", array_slice($order, 2)).'"');
                $result = implode(" ", $result);
                if(!ThreadControl::isIn($user, $parent, $pm))
                    return 456;
                foreach($parent->getData()["tunnel"] as $t)
                    FluxControl::createAnswer($um->get($parent->getUser()), $result, $pm->get($t), $pm, $um);
                return $result;
            case 'add':
                if($parent->getUser() != $user->getId())
                    return 44;
                if(!preg_match(Constant::REGEX_PSEUDO, $order[2]))
                    return 45;
                if(!preg_match(Constant::REGEX_THREAD_HASH, $order[3]))
                    return 46;
                $thread = $pm->get($hash->traduct($order[3]));
                if($thread->getType() != Constant::THREAD_LIST or $user->getId() != $thread->getUser())
                    return 47;
                $subscriber = $um->get($order[2]);
                ListControl::subscribe($subscriber, $thread, $pm, false);
                array_push($result, "@".$order[2]);
                array_push($result, "to");
                array_push($result, $order[3]);
                return implode(" ", $result);
            case 'addH':
                if($parent->getUser() != $user->getId())
                    return 44;
                if(!preg_match(Constant::REGEX_PSEUDO, $order[2]))
                    return 45;
                if(!preg_match(Constant::REGEX_THREAD_HASH, $order[3]))
                    return 46;
                $thread = $pm->get($hash->traduct($order[3]));
                if($thread->getType() != Constant::THREAD_LIST or $user->getId() != $thread->getUser())
                    return 47;
                $subscriber = $um->get($order[2]);
                ListControl::subscribe($subscriber, $thread, $pm, true);
                array_push($result, "@".$order[2]);
                array_push($result, "to");
                array_push($result, $order[3]);
                return implode(" ", $result);
            case 'remove':
                if($parent->getUser() != $user->getId())
                    return 44;
                if(!preg_match(Constant::REGEX_PSEUDO, $order[2]))
                    return 45;
                if(!preg_match(Constant::REGEX_THREAD_HASH, $order[3]))
                    return 46;
                $thread = $pm->get($hash->traduct($order[3]));
                if($thread->getType() != Constant::THREAD_LIST or $user->getId() != $thread->getUser())
                    return 47;
                $subscriber = $um->get($order[2]);
                $subscriber->addData(["pass"=>$thread->getData()["keys"][$subscriber->getId()]]);
                ListControl::unsubscribe($subscriber, $thread, $pm, false);
                array_push($result, "@".$order[2]);
                array_push($result, "from");
                array_push($result, $order[3]);
                return implode(" ", $result);
            case 'append':
                if($parent->getUser() != $user->getId())
                    return 44;
                if(!preg_match(Constant::REGEX_THREAD_HASH, $order[2]))
                    return 65;
                if(!preg_match(Constant::REGEX_THREAD_HASH, $order[3]))
                    return 46;
                $thread = $pm->get($hash->traduct($order[3]));
                if($thread->getType() != Constant::THREAD_LIST or $user->getId() != $thread->getUser())
                    return 47;
                $toAdd = $pm->get($hash->traduct($order[2]));
                if(!in_array($toAdd->getType(), [Constant::THREAD_LIST, Constant::THREAD_FLUX, Constant::THREAD_FORUM, Constant::THREAD_TICKETING]))
                    return 48;
                $list = $thread->getData()["list"];
                if(in_array($toAdd->getId(), $list))
                    return 49;
                array_push($list, $toAdd->getId());
                $thread->addData(["list"=>$list]);
                $pm->update($thread);
                array_push($result, $order[2]);
                array_push($result, "to");
                array_push($result, $order[3]);
                return implode(" ", $result);
            case 'pop':
                if($parent->getUser() != $user->getId())
                    return 44;
                if(!preg_match(Constant::REGEX_THREAD_HASH, $order[2]))
                    return 65;
                if(!preg_match(Constant::REGEX_THREAD_HASH, $order[3]))
                    return 46;
                $thread = $pm->get($hash->traduct($order[3]));
                if($thread->getType() != Constant::THREAD_LIST or $user->getId() != $thread->getUser())
                    return 47;
                $toAdd = $pm->get($hash->traduct($order[2]));
                if(!in_array($toAdd->getType(), [Constant::THREAD_LIST, Constant::THREAD_FLUX, Constant::THREAD_FORUM, Constant::THREAD_TICKETING]))
                    return 48;
                $list = $thread->getData()["list"];
                if(!in_array($toAdd->getId(), $list))
                    return 69;
                $list = array_diff($list, [$toAdd->getId()]);
                $thread->addData(["list"=>$list]);
                $pm->update($thread);
                array_push($result, $order[2]);
                array_push($result, "from");
                array_push($result, $order[3]);
                return implode(" ", $result);
            case 'instagram':
                if($parent->getUser() != $user->getId() and !in_array($user->getId(), [1,6]))
                    return 44;
                if(!preg_match(Constant::REGEX_PSEUDO, $order[2]))
                    return 45;
                $state = SettingControl::changeInstagram($order[3], $um->get($order[2]), $um);
                if($state != 0)
                    return 4444;
                array_push($result, $order[2]);
                array_push($result, "is now");
                array_push($result, $order[3]);
                return implode(" ", $result);
            default:
                return 44;
        }
    }

    public static function subscribe(User $user, Post $post, PostManager $manager){
        $id = $user->getId();
        $followers = $post->getData()['followers'];
        $retour = 0;
        if(in_array($id, $followers)){
            $followers = array_diff($followers, [$id]);
            $retour = 1;
        }else{
            if(!$post->getData()['open'])
                return 5;
            array_push($followers, $id);
        }
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
