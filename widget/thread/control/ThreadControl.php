<?php
class ThreadControl{
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

    public static function open(User $user, Post $post, $open, PostManager $manager){
        if($user->getId() != $post->getUser())
            return Constant::ERROR_CODE_USER_WRONG;
        if($open == "yes")
            $post->addData(["writers"=>[]]);
        else
            $post->addData(["writers"=>[$user->getId()]]);
        $manager->update($post);
        return 0;
    }

    public static function delete(User $user, Post $post, PostManager $manager){
        if($user->getId() != $post->getUser())
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
            default:
                return '';
        }
    }

    public static function getSubscribers(User $user, Post $post, UserManager $manager){
        $final = [];
        $content = self::getInfluence($post);
        $save = $post->getData()[$content];
        foreach(array_reverse($save) as $inter){
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
        }
        return $final;
    }

    public static function list(User $user, PostManager $manager){
        $final = [];
        $list = $manager->getList();
        foreach(array_reverse($list) as $thread){
            if(
                ($thread->getType() == Constant::THREAD_FORUM or $thread->getType() == Constant::THREAD_TICKETING or $thread->getType() == Constant::THREAD_FLUX) and
                (
                    $thread->getUser() == $user->getId() or
                    ($thread->getType() == Constant::THREAD_FORUM and in_array($user->getId(), $thread->getData()['followers'])) or
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

    public static function subscribe($content, User $user, Post $post, PostManager $manager){
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
        return 0;
    }

    public static function unsubscribe($content, User $user, Post $post, PostManager $manager){
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
        return 0;
    }

    public static function hasSubscribe($content, User $user, Post $post){
        if(in_array($user->getId(), $post->getData()[$content]))
            return 1;
        return 0;
    }
}
