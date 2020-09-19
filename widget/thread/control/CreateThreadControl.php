<?php
class createThreadControl{
    const WEAK_LIMIT = 0;
    // const WEAK_LIMIT = 20;
    const LIMIT = 77 + WEAK_LIMIT;
    const UNLIMITED = [1];

    public static function getObject($thread){
        $retour = "";
        switch ($thread) {
            case 'flux':
                $retour .=
                '<input type="hidden" name="origin" value="flux"/>
                <input class="input large" type="text" name="title" placeholder="Title"/><br/>
                <textarea name="intro"></textarea>';
                break;
            case 'forum':
                $retour .=
                '<input type="hidden" name="origin" value="forum"/>
                <input type="file" name="cover" accept="image/x-png,image/jpeg" class="vide"/>
                <img class="large" src="style/upload_image.png" alt="preview"/><br/>
                <input class="input  noBorder" type="text" name="title" placeholder="Title"/>';
                break;
            case 'ticketing':
                $retour .=
                '<input type="hidden" name="origin" value="ticketing"/>
                <input class="input large" type="text" name="title" placeholder="Title"/>
                <input type="date" name="when" class="input"/>';
                break;
            case 'list':
                $retour .=
                '<input type="hidden" name="origin" value="list"/>
                <input class="input large" type="text" name="title" placeholder="Title"/>
                <input class="input large" type="text" name="thread" placeholder="links"/>
                <input type="hidden" name="list"/>
                <div id="preview" class="large"></div>';
                break;
            default:
                $retour .= '';
                break;
        }
        return $retour;
    }

    public static function hasLimit(User $user, PointManager $lm){
        if(!LicenceControl::isValide($user, $lm) and (count($user->getData()["threads"]) >= self::WEAK_LIMIT))
            return Constant::ERROR_CODE_CREATE_THREAD_LIMIT;
        if(LicenceControl::isValide($user, $lm) and (count($user->getData()["threads"]) >= self::LIMIT and !in_array($user->getId(), self::UNLIMITED)))
            return Constant::ERROR_CODE_CREATE_THREAD_LIMIT;
        return 0;
    }

    public static function indexThreadFromStart(User $user, UserManager $um, PostManager $pm){
        global $hash;
        $tab = [];
        foreach($pm->getList() as $thread){
            if($thread->getUser() == $user->getId() and in_array($thread->getType(), [Constant::THREAD_FLUX, Constant::THREAD_LIST, Constant::THREAD_FORUM, Constant::THREAD_TICKETING]))
                array_push($tab, $thread->getId());
                $hash->add($thread->getId());
        }
        $user->addData(["threads"=>$tab]);
        $um->update($user);
    }

    public static function indexThread($threadId, User $user, Post $post, UserManager $um, PostManager $pm){
        global $hash;
        $thread = $pm->get($threadId);
        if($post->getUser() != $thread->getUser() or $post->getData()['title'] != $thread->getData()['title'])
            return self::indexThreadFromStart($user, $um, $pm);
        $tab = $user->getData()["threads"];
        array_push($tab, $threadId);
        $user->addData(["threads"=>$tab]);
        $um->update($user);
        $hash->add($thread->getId());
    }

    public static function createFlux(User $user, $title, $intro, UserManager $um, PostManager $pm, PointManager $lm, $limit=true){
        global $hash;
        if($limit and !LicenceControl::isValide($user, $lm) and (count($user->getData()["threads"]) >= self::WEAK_LIMIT))
            return Constant::ERROR_CODE_CREATE_THREAD_LIMIT;
        if(LicenceControl::isValide($user, $lm) and (count($user->getData()["threads"]) >= self::LIMIT and !in_array($user->getId(), self::UNLIMITED)))
            return Constant::ERROR_CODE_CREATE_THREAD_LIMIT;
        if(!preg_match(Constant::REGEX_FORMAT_TITLE, $title))
            return Constant::ERROR_CODE_THREAD_TITLE;
        if(!preg_match("#^.{1,1000}$#s", $intro))
            return Constant::ERROR_CODE_THREAD_LENGTH;
        $post = new Post();
        $post->setUser($user->getId());
        $post->setField($intro);
        $post->setType(1);
        $post->addData(["title"=>$title]);
        $post->addData(["writers"=>[]]);
        $post->addData(["subscribers"=>[]]);
        $post->addData(["open"=>true]);
        $post->addData(["head"=>0]);
        $pm->add($post);
        $lid = $pm->lastId();
        self::indexThread($lid, $user, $post, $um, $pm);
        return $hash->get($lid);
    }

    public static function createForum(User $user, $title, $cover, UserManager $um, PostManager $pm, PointManager $lm, $path=""){
        global $hash;
        if(!LicenceControl::isValide($user, $lm) and (count($user->getData()["threads"]) >= self::WEAK_LIMIT))
            return Constant::ERROR_CODE_CREATE_THREAD_LIMIT;
        if(LicenceControl::isValide($user, $lm) and (count($user->getData()["threads"]) >= self::LIMIT and !in_array($user->getId(), self::UNLIMITED)))
            return Constant::ERROR_CODE_CREATE_THREAD_LIMIT;
        if(!preg_match(Constant::REGEX_FORMAT_TITLE, $title))
            return Constant::ERROR_CODE_THREAD_TITLE;
        $extension = substr(strrchr($cover['name'],'.'),1);
        $rename = $user->getID().achage(32).'.'.$extension;
        $dest = $path.'media/forum/cover/'.$rename;
        $verif = upload($cover, $dest, 1048576, ["png", "jpg", "jpeg"]);
        switch($verif){
            case 0:
                return 15;
            case 1:
                $post = new Post();
                $post->setUser($user->getId());
                $post->setField($rename);
                $post->setType(0);
                $post->addData(["title"=>$title]);
                $post->addData(["writers"=>[]]);
                $post->addData(["followers"=>[]]);
                $post->addData(["open"=>true]);
                $post->addData(["head"=>0]);
                $pm->add($post);
                $pm->add($post);
                $lid = $pm->lastId();
                self::indexThread($lid, $user, $post, $um, $pm);
                return $hash->get($lid);
            case 701:
                return 12;
            case 702:
                return 13;
            case 703:
                return 14;
            default:
                break;
        }
    }

    public static function createTicketing(User $user, $title, $date, UserManager $um, PostManager $pm, PointManager $lm){
        global $hash;
        if(!LicenceControl::isValide($user, $lm) and (count($user->getData()["threads"]) >= self::WEAK_LIMIT))
            return Constant::ERROR_CODE_CREATE_THREAD_LIMIT;
        if(LicenceControl::isValide($user, $lm) and (count($user->getData()["threads"]) >= self::LIMIT and !in_array($user->getId(), self::UNLIMITED)))
            return Constant::ERROR_CODE_CREATE_THREAD_LIMIT;
        if(!preg_match(Constant::REGEX_FORMAT_TITLE, $title))
            return Constant::ERROR_CODE_THREAD_TITLE;
        if(!checkIsAValidDate($date))
            return Constant::ERROR_CODE_THREAD_DATE;
        $post = new Post();
        $post->setUser($user->getId());
        $post->setField($date);
        $post->setType(2);
        $post->addData(["title"=>$title]);
        $post->addData(["writers"=>[]]);
        $post->addData(["tickets"=>[]]);
        $post->addData(["open"=>true]);
        $pm->add($post);
        $pm->add($post);
        $lid = $pm->lastId();
        self::indexThread($lid, $user, $post, $um, $pm);
        return $hash->get($lid);
    }

    public static function createList(User $user, $title, $list, UserManager $um, PostManager $pm, PointManager $lm){
        global $hash;
        if(!LicenceControl::isValide($user, $lm) and (count($user->getData()["threads"]) >= self::WEAK_LIMIT))
            return Constant::ERROR_CODE_CREATE_THREAD_LIMIT;
        if(LicenceControl::isValide($user, $lm) and (count($user->getData()["threads"]) >= self::LIMIT and !in_array($user->getId(), self::UNLIMITED)))
            return Constant::ERROR_CODE_CREATE_THREAD_LIMIT;
        if(!preg_match(Constant::REGEX_FORMAT_TITLE, $title))
            return Constant::ERROR_CODE_THREAD_TITLE;
        $post = new Post();
        $post->setUser($user->getId());
        $post->setField("null");
        $post->setType(Constant::THREAD_LIST);
        $post->addData(["title"=>$title]);
        $post->addData(["list"=>$list]);
        $post->addData(["writers"=>[]]);
        $post->addData(["followers"=>[]]);
        $post->addData(["open"=>true]);
        $pm->add($post);
        $pm->add($post);
        $lid = $pm->lastId();
        self::indexThread($lid, $user, $post, $um, $pm);
        return $hash->get($lid);
    }
}
