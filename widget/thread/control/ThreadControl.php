<?php
class ThreadControl{
    public static function slotLength(Post $post){
        $result = 1;
        if(isset($post->getData()["addresses"]))
            $result += count($post->getData()["addresses"]);
        return $result;
    }

    public static function takenSlots(User $user, PostManager $pm){
        $diff = [];
        if(isset($user->getData()["starter"]))
            $diff = $user->getData()["starter"];
        $result = count(array_diff($user->getData()["threads"], $diff));
        foreach($user->getData()["posters"] as $num){
            $poster = $pm->get($num);
            if(!is_int($poster))
                $result += self::slotLength($poster);
        }
        return $result;
    }

    public static function allSlots(User $user){
        return ($user->getData()['slots']+CreateThreadControl::LIMIT);
    }

    public static function slotStatut(User $user, PostManager $pm){
        return '<span id="availableSlots"><span class="deno">'.self::takenSlots($user, $pm).'</span><span class="vert on">/</span><span class="vert nume">'.self::allSlots($user).'</span></span>';
    }

    public static function addDuplica(User $user, Post $post, $coord, PostManager $pm, PointManager $lm){
        $addresses = [];
        $inter = [];
        if($coord == "" or $coord == null)
            return 0;
        if($post->getUser() != $user->getId())
            return Constant::ERROR_CODE_USER_WRONG;
        if(CreateThreadControl::hasLimit($user, $lm))
            return Constant::ERROR_CODE_CREATE_THREAD_LIMIT;
        $frag = explode(" || ", $coord);
        if($frag[0] == null or $frag[0] == "")
            return 297;
        if(!preg_match("/^-?[0-9]{1,7}\.[0-9]+$/", $frag[1]) or !preg_match("/^-?[0-9]{1,7}\.[0-9]+$/", $frag[2]))
            return 296;
        $inter["address"] = $frag[0];
        $inter["lat"] = $frag[1];
        $inter["long"] = $frag[2];
        if(isset($post->getData()['addresses']))
            $addresses = $post->getData()['addresses'];
        array_push($addresses, $inter);
        $post->addData(["addresses"=>$addresses]);
        // var_dump($post);
        $pm->update($post);
        return 0;
    }

    public static function removeDuplica(User $user, Post $post, $lat, $long, PostManager $pm){
        $addresses = [];
        if($post->getUser() != $user->getId())
            return Constant::ERROR_CODE_USER_WRONG;
        if(!isset($post->getData()['addresses']))
            return 0;
        foreach($post->getData()['addresses'] as $address){
            if($address["lat"] != $lat or $address["long"] != $long){
                array_push($addresses, $address);
            }
        }
        $post->addData(["addresses"=>$addresses]);
        // var_dump($post);
        $pm->update($post);
        return 0;
    }

    public static function showDuplica(Post $post){
        $result = "";
        if(!isset($post->getData()['addresses']))
            return "";
        foreach($post->getData()['addresses'] as $address){
            $result .= '<span class="addressDup">'.$address["address"].' <span class="minus" lat="'.$address["lat"].'" long="'.$address["long"].'">-</span></span><br>';
        }
        return $result;
    }

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

    public static function setInput(User $user, Post $post, $threadId, PostManager $manager){
        global $hash;
        if($post->getUser() != $user->getId())
            return Constant::ERROR_CODE_USER_WRONG;
        if($threadId == "" or $threadId == NULL){
            $post->removeData("input");
            $manager->update($post);
            return 0;
        }
        $tab = [];
        $result = 0;
        foreach (explode(" ", $threadId) as $thread){
            if($thread == "" or $thread == NULL)
                continue;
            $inter = $hash->traduct($thread);
            $thread = $manager->get($inter);
            if(is_int($thread) or in_array($inter, $tab))
                continue;
            if(!in_array($thread->getType(), [Constant::THREAD_FLUX, Constant::THREAD_FORUM, Constant::THREAD_LIST, Constant::THREAD_TICKETING])){
                // return Constant::ERROR_CODE_NOT_FOUND;
                $result = 1;
                continue;
            }
            array_push($tab, $inter);
            $tunnel = [];
            if(isset($thread->getData()["tunnelv2"]))
                $tunnel = $thread->getData()["tunnelv2"];
            self::setTunnel($thread, array_merge($tunnel,[$post->getId()]), $manager);
        }
        $post->addData(["input"=>$tab]);
        $manager->update($post);
        return $result;
    }

    public static function setOutput(User $user, Post $post, $fluxId, PostManager $manager){
        global $hash;
        if($post->getUser() != $user->getId())
            return Constant::ERROR_CODE_USER_WRONG;
        if($fluxId == "" or $fluxId == NULL){
            $post->removeData("output");
            $manager->update($post);
            return 0;
        }
        $tab = [];
        $result = 0;
        foreach (explode(" ", $fluxId) as $id){
            if($id == "" or $id == NULL)
                continue;
            $inter = $hash->traduct($id);
            $thread = $manager->get($inter);
            if(is_int($thread) or in_array($inter, $tab))
                continue;
            if(!in_array($thread->getType(), [Constant::THREAD_FORUM, Constant::THREAD_LIST, Constant::THREAD_TICKETING])){
                // return Constant::ERROR_CODE_NOT_FOUND;
                $result = 1;
                continue;
            }
            if($thread->getUser() != $user->getId()){
                // return Constant::ERROR_CODE_USER_WRONG;
                $result = 1;
                continue;
            }
            array_push($tab, $inter);
        }
        $post->addData(["output"=>$tab]);
        $manager->update($post);
        return $result;
    }

    public static function setTunnel(Post $post, $fluxId, PostManager $manager){
        $tab = [];
        $result = 0;
        foreach ($fluxId as $inter){
            $flux = $manager->get($inter);
            if(is_int($flux) or in_array($flux->getId(), $tab))
                continue;
            if($flux->getType() != Constant::THREAD_FLUX){
                // return Constant::ERROR_CODE_NOT_FOUND;
                $result = 1;
                continue;
            }
            array_push($tab, $flux->getId());
        }
        $post->addData(["tunnelv2"=>$tab]);
        $manager->update($post);
        return $result;
    }

    public static function setLock(User $user, Post $post, $registerId, PostManager $manager){
        global $hash;
        if($post->getUser() != $user->getId())
            return Constant::ERROR_CODE_USER_WRONG;
        if($registerId == "" or $registerId == NULL){
            $post->removeData("lock");
            $manager->update($post);
            return 0;
        }
        $tab = [];
        $result = 0;
        foreach (explode(" ", $registerId) as $id){
            if($id == "" or $id == NULL)
                continue;
            $inter = $hash->traduct($id);
            $register = $manager->get($inter);
            if(is_int($register) or in_array($inter, $tab))
                continue;
            if($register->getType() != Constant::THREAD_TICKETING){
                // return Constant::ERROR_CODE_NOT_FOUND;
                $result = 1;
                continue;
            }
            if($register->getUser() != $user->getId()){
                // return Constant::ERROR_CODE_USER_WRONG;
                $result = 1;
                continue;
            }
            array_push($tab, $inter);
        }
        $post->addData(["lock"=>$tab]);
        $manager->update($post);
        return $result;
    }

    public static function isInLock(User $user, Post $parent, PostManager $pm){
        if(!isset($parent->getData()["lock"]))
            return true;
        foreach($parent->getData()["lock"] as $registerId){
            if(TicketingControl::hasValidate($user, $pm->get($registerId)) == 1)
                return true;
        }
        return false;
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
    public static function setRenew(User $user, Post $post, $renew, PostManager $manager){
        if($user->getId() != $post->getUser())
            return Constant::ERROR_CODE_USER_WRONG;
        if($renew == "yes")
            $post->addData(["renew"=>true]);
        else
            $post->addData(["renew"=>false]);
        $manager->update($post);
        return 0;
    }
    public static function setNotify(User $user, Post $post, $notify, PostManager $manager, PointManager $lm){
        $result = 0;
        if($user->getId() != $post->getUser())
            return Constant::ERROR_CODE_USER_WRONG;
        if($notify == "yes"){
            if(LicenceControl::isValide($user, $lm))
                $post->addData(["notify"=>true]);
            else{
                $result = 1;
                $post->addData(["notify"=>false]);
            }
        }
        else
            $post->addData(["notify"=>false]);
        $manager->update($post);
        return $result;
    }
    public static function setAlert(User $user, Post $post, $alert, PostManager $manager, PointManager $lm){
        $result = 0;
        if($user->getId() != $post->getUser())
            return Constant::ERROR_CODE_USER_WRONG;
        if($alert == "yes"){
            if(LicenceControl::isValide($user, $lm))
                $post->addData(["alert"=>true]);
            else{
                $result = 1;
                $post->addData(["alert"=>false]);
            }
        }
        else
            $post->addData(["alert"=>false]);
        $manager->update($post);
        return $result;
    }

    public static function isAlert(Post $post){
        return isset($post->getData()["alert"]) and $post->getData()["alert"];
    }

    public static function isOpen(Post $post){
        return $post->getData()["open"];
    }

    public static function setMode(User $user, Post $post, $mode, $right, PostManager $manager){
        if($user->getId() != $post->getUser())
            return Constant::ERROR_CODE_USER_WRONG;
        if($right == "lock1")
            $post->addData([$mode=>2]);
        elseif($right == "everyone")
            $post->addData([$mode=>1]);
        else
            $post->addData([$mode=>0]);
        $manager->update($post);
        return 0;
    }

    public static function checkMode(User $user, Post $post, $mode, PostManager $pm){
        if(!isset($post->getData()[$mode]))
            return true;
        return $user->getId() == $post->getUser() or $post->getData()[$mode] == 1 or ($post->getData()[$mode] == 2 and self::isInLock($user, $post, $pm));
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
        if($user->getId() != $post->getUser())
            return Constant::ERROR_CODE_USER_WRONG;
        if(!isset($post->getData()["originalField"]))
            $post->addData(["originalField"=>$post->getField()]);
        $post->setField($answer);
        $manager->update($post);
        return 0;
    }

    public static function delete(User $user, Post $post, UserManager $um, PostManager $manager){
        if($user->getId() != $post->getUser() and !self::isAdmin($user, $post, $manager))
            return Constant::ERROR_CODE_USER_WRONG;
        $post->setActive(0);
        $manager->update($post);
        $tab = $user->getData()['threads'];
        $tab = array_diff($tab, [$post->getId()]);
        $user->addData(["threads"=>$tab]);
        $tab = $user->getData()['posters'];
        $tab = array_diff($tab, [$post->getId()]);
        $user->addData(["posters"=>$tab]);
        $um->update($user);
        return 0;
    }

    public static function getType(Post $post){
        switch($post->getType()){
            case Constant::THREAD_FLUX:
                return "flux";
            case Constant::THREAD_FORUM:
                return "blog";
            case Constant::THREAD_TICKETING:
                return "register";
            case Constant::THREAD_LIST:
                return "list";
            case Constant::THREAD_POSTER:
                return "poster";
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
            case Constant::THREAD_POSTER:
                return 'viewers';
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
        $result = [];
        if(isset($user->getData()["threads"]))
            $final = array_merge($final, $user->getData()["threads"]);
        if(isset($user->getData()["following"]))
            $final = array_merge($final, $user->getData()["following"]);
        rsort($final);
        $final = array_unique($final);
        foreach($final as $num){
            $thread = $manager->get(intval($num));
            if(!is_int($thread))
                array_push($result, $thread);
        }
        return $result;
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
        switch ($post->getType()) {
            case Constant::THREAD_FLUX:
                $tab[$post->getId()] = $post->getData()["head"];
                break;
            case Constant::THREAD_FORUM:
                $tab[$post->getId()] = $post->getData()["head"];
                break;
            case Constant::THREAD_TICKETING:
                $tab[$post->getId()] = count($post->getData()[self::getInfluence($post)]);
                break;
            case Constant::THREAD_LIST:
                $tab[$post->getId()] = count($post->getData()["list"]);
                break;
            default:
                break;
        }
        $user->addData(["number"=>$tab]);
        $um->update($user);
        return 0;
    }
}
