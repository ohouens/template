<?php
class ThreadControl{
    public static function addList(Post $post, Post $parent, PostManager $pm){
        if(!in_array($parent->getType(), [Constant::THREAD_FORUM, Constant::THREAD_FLUX]))
            return 304;
        $pm->add($post);
        $newHead = $pm->lastId();
        $next = $parent->getData()["head"];
        $parent->addData(["head"=>$newHead]);
        $parent->addData(["number"=>$parent->getData()["number"]+1]);
        $pm->update($parent);
        $toUpdate = $pm->get($newHead);
        if($toUpdate->getCreation() != $post->getCreation() or $toUpdate->getField() != $post->getField())
            return ThreadControl::updateList($parent, $pm);
        $toUpdate->setId($newHead);
        $toUpdate->addData(["next"=>$next]);
        $pm->update($toUpdate);
        return 0;
    }

    public static function updateList(Post $post, PostManager $pm){
        $list = $pm->getList();
		if(in_array($post->getType(), [Constant::THREAD_FORUM, Constant::THREAD_FLUX])){
			foreach($list as $inter){
				if($inter->getType() == Constant::THREAD_ANSWER and $inter->getData()['parent'] == $post->getId() and !isset($inter->getData()['next'])){
					$inter->addData(["next" => $post->getData()["head"]]);
					$post->addData(["head" => $inter->getId()]);
                    $pm->update($inter);
        			$pm->update($post);
				}
			}
		}
        return 0;
    }

    public static function initList(Post $post, PostManager $pm){
		if(in_array($post->getType(), [Constant::THREAD_FORUM, Constant::THREAD_FLUX])){
            $post->addData(["head"=>0]);
            $pm->update($post);
			foreach($pm->getList() as $inter){
				if($inter->getType() == Constant::THREAD_ANSWER and $inter->getData()['parent'] == $post->getId()){
					$inter->addData(["next" => $post->getData()["head"]]);
					$post->addData(["head" => $inter->getId()]);
                    $pm->update($inter);
        			$pm->update($post);
				}
			}
		}
        return 0;
    }

    public static function isIn(User $user, Post $parent, PostManager $pm){
        if(!isset($parent->getData()["in"]))
            return true;
        foreach($parent->getData()["in"] as $registerId){
            if(TicketingControl::hasValidate($user, $pm->get($registerId)) == 1)
                return true;
        }
        return false;
    }

    public static function setTunnel(User $user, Post $post, $fluxId, PostManager $manager){
        global $hash;
        if($fluxId == "" or $fluxId == NULL){
            $post->removeData("tunnel");
            $manager->update($post);
            return 0;
        }
        $tab = [];
        foreach (explode(" ", $fluxId) as $flux){
            $inter = $hash->traduct($flux);
            $flux = $manager->get($inter);
            if(in_array($inter, $tab))
                continue;
            if($flux->getType() != Constant::THREAD_FLUX)
                // return Constant::ERROR_CODE_NOT_FOUND;
                continue;
            if($flux->getUser() != $user->getId() or $post->getUser() != $user->getId())
                // return Constant::ERROR_CODE_USER_WRONG;
                continue;
            array_push($tab, $inter);
        }
        $post->addData(["tunnel"=>$tab]);
        $manager->update($post);
        return 0;
    }

    public static function setIn(User $user, Post $post, $registerId, PostManager $manager){
        global $hash;
        if($registerId == "" or $registerId == NULL){
            $post->removeData("in");
            $manager->update($post);
            return 0;
        }
        $tab = [];
        foreach (explode(" ", $registerId) as $register){
            $inter = $hash->traduct($register);
            $register = $manager->get($inter);
            if(in_array($inter, $tab))
                continue;
            if($register->getType() != Constant::THREAD_TICKETING)
                // return Constant::ERROR_CODE_NOT_FOUND;
                continue;
            if($register->getUser() != $user->getId() or $post->getUser() != $user->getId())
                // return Constant::ERROR_CODE_USER_WRONG;
                continue;
            array_push($tab, $inter);
        }
        $post->addData(["in"=>$tab]);
        $manager->update($post);
        return 0;
    }

    public static function setOpen(User $user, Post $post, $open, PostManager $manager){
        if($user->getId() != $post->getUser())
            return Constant::ERROR_CODE_USER_WRONG;
        if($open == "yes")
            $post->addData(["open"=>true]);
        else
            $post->addData(["open"=>false]);
        $manager->update($post);
        return 0;
    }
    public static function setNotify(User $user, Post $post, $notify, PostManager $manager){
        if($user->getId() != $post->getUser())
            return Constant::ERROR_CODE_USER_WRONG;
        if($notify == "yes")
            $post->addData(["notify"=>true]);
        else
            $post->addData(["notify"=>false]);
        $manager->update($post);
        return 0;
    }

    public static function isOpen(Post $post){
        return $post->getData()["open"];
    }

    public static function setMode(User $user, Post $post, $mode, $right, PostManager $manager){
        if($user->getId() != $post->getUser())
            return Constant::ERROR_CODE_USER_WRONG;
        if($right == "everyone")
            $post->addData([$mode=>1]);
        else
            $post->addData([$mode=>0]);
        $manager->update($post);
        return 0;
    }

    public static function checkMode(User $user, Post $post, $mode){
        if(!isset($post->getData()[$mode]))
            return true;
        return $user->getId() == $post->getUser() or $post->getData()[$mode] == 1;
    }

    public static function setWriters(User $user, Post $post, $writers, PostManager $manager){
        if($user->getId() != $post->getUser())
            return Constant::ERROR_CODE_USER_WRONG;
        if(!preg_match("#^([a-z0-9_]{1,20} ?){0,}$#", $writers))
            return Constant::ERROR_CODE_PSEUDO_LENGTH;
        $post->addData(["writers"=>explode(" ", $writers)]);
        $manager->update($post);
        return 0;
    }

    public static function getWriters(Post $post){
        return implode(" ", $post->getData()["writers"]);
    }

    public static function isAdmin(User $user, Post $post, PostManager $manager){
        if($post->getType() != Constant::THREAD_ANSWER)
            return false;
        $parent = $manager->get($post->getData()["parent"]);
        return $user->getId() == $parent->getUser();
    }

    public static function edit(User $user, Post $post, $answer, PostManager $manager){
        if($user->getId() != $post->getUser() and !self::isAdmin($user, $post, $manager))
            return Constant::ERROR_CODE_USER_WRONG;
        if(!isset($post->getData()["originalField"]))
            $post->addData(["originalField"=>$post->getField()]);
        $post->setField($answer);
        $manager->update($post);
        return 0;
    }

    public static function delete(User $user, Post $post, PostManager $manager){
        if($user->getId() != $post->getUser() and !self::isAdmin($user, $post, $manager))
            return Constant::ERROR_CODE_USER_WRONG;
        $post->setActive(0);
        $manager->update($post);
        return 0;
    }

    public static function getType(Post $post){
        switch($post->getType()){
            case Constant::THREAD_FLUX:
                return "flux";
            case Constant::THREAD_FORUM:
                return "forum";
            case Constant::THREAD_TICKETING:
                return "ticketing";
            default:
                return 'thread';
        }
    }

    public static function getInfluence(Post $post){
        switch($post->getType()) {
            case Constant::THREAD_FLUX:
                return 'subscribers';
            case Constant::THREAD_FORUM:
                return 'followers';
            case Constant::THREAD_TICKETING:
                return 'tickets';
            case Constant::THREAD_LIST:
                return 'followers';
            default:
                return '';
        }
    }

    public static function getSubscribers(User $user, Post $post, UserManager $manager){
        $final = [];
        $content = self::getInfluence($post);
        $save = $post->getData()[$content];
        foreach(array_reverse($save) as $inter){
            try{
                // if(is_int($inter))
                //     throw new Exception("Error Processing Request");
                if(preg_match(Constant::REGEX_EMAIL, $inter)){
                    if($user->getId() == $post->getUser()){
                        $tmp = new User(["email"=>$inter]);
                        $tmp->addData(["isMail"=>True]);
                        array_push($final, $tmp);
                    }
                }else{
                    $tmp = $manager->get($inter);
                    $tmp->addData(["isMail"=>False]);
                    array_push($final, $tmp);
                }
            }catch(Exception $e){

            }
        }
        return $final;
    }

    public static function list(User $user, PostManager $manager){
        $final = [];
        $list = $manager->getList();
        foreach(array_reverse($list) as $thread){
            if(
                (in_array($thread->getType(), [Constant::THREAD_FORUM ,Constant::THREAD_TICKETING ,Constant::THREAD_FLUX, Constant::THREAD_LIST])) and
                (
                    $thread->getUser() == $user->getId() or
                    ($thread->getType() == Constant::THREAD_FORUM and in_array($user->getId(), $thread->getData()['followers'])) or
                    ($thread->getType() == Constant::THREAD_LIST and in_array($user->getId(), $thread->getData()['followers'])) or
                    ($thread->getType() == Constant::THREAD_TICKETING and in_array($user->getId(), $thread->getData()['tickets'])) or
                    ($thread->getType() == Constant::THREAD_FLUX and in_array($user->getId(), $thread->getData()['subscribers']))
                )
            )
		         array_push($final, $thread);
		}
        return $final;
    }

    public static function getId(User $user){
        if($user->getPseudo() == "")
            return $user->getEmail();
        return $user->getId();
    }

    public static function getUser($user, UserManager $um){
        if(preg_match(Constant::REGEX_EMAIL, $user))
            return new User(['email'=>$user]);
        else
            return $um->get($user);
    }

    public static function subscribe($content, User $user, Post $post, PostManager $manager, UserManager $um){
        $id = self::getId($user);
        $key = $key = achage(32);
        $save = $post->getData()[$content];
        if(in_array($id, $save))
            return 1;
        array_push($save, $id);
        $post->removeData($content);
        $post->addData([$content => $save]);
        $pass = $post->getData()['keys'];
        $pass[$id] = $key;
        $post->addData(['keys' => $pass]);
        $manager->update($post);
        $following = [];
        if(isset($user->getData()["following"]))
            $following = $user->getData()["following"];
        array_push($following, $post->getId());
        $user->addData(["following"=>$following]);
        $um->update($user);
        return 0;
    }

    public static function unsubscribe($content, User $user, Post $post, PostManager $manager, UserManager $um){
        $id = self::getId($user);
        $save = $post->getData()[$content];
        $keys = $post->getData()['keys'];
        if(!in_array($id, $save))
            return 1;
        if($keys[$id] != $user->getData()['pass'])
            return 2;
        $save = array_diff($save, [$id]);
        unset($keys[$id]);
        $post->removeData($content);
        $post->addData([$content => $save]);
        $post->addData(['keys' => $keys]);
        $manager->update($post);
        if(!isset($user->getData()["following"]))
            return 0;
        $following = $user->getData()["following"];
        $following = array_diff($following, [$post->getId()]);
        $user->addData(["following"=>$following]);
        $user->removeData("pass");
        $um->update($user);
        return 0;
    }

    public static function hasSubscribe($content, User $user, Post $post){
        if(in_array($user->getId(), $post->getData()[$content]))
            return 1;
        return 0;
    }

    public static function updateNumber(User $user, Post $post, UserManager $um){
        $tab = [];
        if(isset($user->getData()["number"]))
            $tab = $user->getData()["number"];
        $tab[$post->getId()] = $post->getData()["number"];
        $user->addData(["number"=>$tab]);
        $um->update($user);
        return 0;
    }
}
