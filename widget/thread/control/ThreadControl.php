<?php
class ThreadControl{
    public static function getSubscribers($content, User $user, Post $post, UserManager $manager){
        $result = "";
        $final = [];
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
        foreach($final as $inter){
            $id = $inter->getId();
            $link = '?page='.$id;
            $display = $inter->getPseudo();
            if($inter->getData()['isMail']){
                $id = $inter->getEmail();
                $link = "mailto:".$id;
                $display = $id;
            }
            $pp = "";
            if(isset($inter->getData()['pp']))
                $pp = '<img class="profilePicture" src="media/user/pp/'.$inter->getData()['pp'].'" alt="profile picture">';
            $delete = "";
            if($user->getId() == $post->getUser()){
                $delete =
                '<a href="?thread='.$post->getId().'&amp;delete='.$id.'">
                    <img class="delete" src="style/icon/wrong.png" alt="delete"/>
                </a>';
            }
            $result .=
            '<p class="">
                <a href="'.$link.'" class="link">
                    '.$pp.'
                    '.$display.'
                </a>
                '.$delete.'
            </p>
            <hr>';
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
