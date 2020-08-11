<?php
class FluxControl{
    public static function read(Post $post, PostManager $manager){
        global $hash;
        $result = "";
        $final = [];
        $cursor = $post->getData()["head"];
        while($cursor != 0){
            $load = $manager->get($cursor);
            array_push($final, $load);
            $cursor = $load->getData()["next"];
        }
        array_push($final, $post);
        foreach($final as $inter){
            $text = preg_replace("/".$hash->get($post->getId())." -> @[\w]{1,20}: /", '', $inter->getField());
            $text = nl2br(htmlspecialchars($text." "));
            $text = preg_replace("#&amp;#", '&', $text);
            $body = preg_replace("/((https?:\/\/)?onisowo.com\/(index.php)?\?thread=(\w{40})(&request=3)?)/", '<a href="$1" class="to" style="color: #abd6f3;">$4</a>', $text);
            $body = preg_replace("/((https?:\/\/)?(www\.)?instagram.com\/[\w?.\/=&#_-]+)/", '<a href="$1" target="_blank">instagram</a>', $body);
            $body = preg_replace("/((https?:\/\/)?(www\.)?linkedin.com\/[\wï?.\/=&#_-]+)/", '<a href="$1" target="_blank">linkedin</a>', $body);
            $body = preg_replace("/((https?:\/\/)?(www\.)?snapchat.com\/[\w?.\/=&#_-]+)/", '<a href="$1" target="_blank">snapchat</a>', $body);
            $body = preg_replace("/((https?:\/\/)?(www\.)?(open\.)?spotify.com\/[\w?.\/=&#_-]+)/", '<a href="$1" target="_blank">spotify</a>', $body);
            $body = preg_replace("/((https?:\/\/)?(www\.)?twitter.com\/[\w?.\/=&#_-]+)/", '<a href="$1" target="_blank">twitter</a>', $body);
            $body = preg_replace("/((https?:\/\/)?(www\.)?pinterest.com\/[\w?.\/=&#_-]+)/", '<a href="$1" target="_blank">pinterest</a>', $body);
            $body = preg_replace("/((https?:\/\/[\w?.\/=&#_-]+)( |<br\/>|<br>|<br \/>))/", '<a href="$2" target="_blank">$2</a>$3', $body);
            $body = preg_replace("#([\w]{40})#", '<a href="index.php?thread=$1" class="to" style="color: #abd6f3;">$1</a>', $body);
            $body = preg_replace("#::(.*)::#", '::<span class="warning" style="color: #8d0d0d;">$1</span>::', $body);
            $body = preg_replace("/([a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*\@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)*\.[a-zA-Z]{2,4})/", '<a href="mailto:$1" class="mailto" style="color: #96a8c0;">$1</a>', $body);
            $body = preg_replace("/((@([a-z0-9_]{0,20}))(:| |<br\/>|<br>|<br \/>))/", '<a href="https://onisowo.com/index.php?page=$3" class="at" style="color: #ff009d;">$2</a>$4', $body);
            $body = substr($body, 0, -1);
            $result .=
            '<p class="fluxMessage" style="border-left: 2px solid grey; padding-left: 1%; padding-top: 5px; padding-bottom: 15px; margin: 0; margin-left: 4px;">
                '.$body.'
            </p>
            <hr class="mark" style="width: 8px; height: 8px; border-radius: 50%; background: grey; margin: 0; display: inline-block;"/>';
        }
        return $result;
    }

    public static function createAnswer(User $user, $answer, Post $parent, PostManager $postManager, UserManager $userManager, $dejavu=[], $racine=true){
        array_push($dejavu, $parent->getId());
        // var_dump($dejavu);
        if($user->getId() != $parent->getUser())
            return Constant::ERROR_CODE_THREAD_WRITE_RIGHT;
        if(!preg_match("#^.{1,520}$#s", $answer))
            return Constant::ERROR_CODE_THREAD_LENGTH;
        $post = new Post();
        $post->setUser($user->getId());
        $post->setField($answer);
        $post->setType(Constant::THREAD_ANSWER);
        $post->addData(["parent"=>$parent->getId()]);
        ThreadControl::addList($post, $parent, $postManager);
        if(self::isNotify($parent))
            self::updateSubscriber($parent, $postManager, $userManager);
        $result = self::recursion($user, $answer, $parent, $postManager, $userManager, $dejavu);
        if(!$racine){
            // echo "recursion:";
            // echo "<br><br><br><br>";
            return $result;
        }
        return 0;
    }

    public static function recursion(User $user, $answer, Post $parent, PostManager $postManager, UserManager $userManager, $dejavu){
        $result = $dejavu;
        if(isset($parent->getData()["tunnelv2"])){
            foreach($parent->getData()["tunnelv2"] as $num){
                $thread = $postManager->get($num);
                if(is_int($thread))
                    continue;
                if(in_array($num, $result)){
                    // echo "deja vu ".$num;
                    continue;
                }
                array_push($result, $num);
                if(in_array($parent->getId(), $thread->getData()["input"])){
                    $head = $postManager->get($thread->getData()['head']);
                    if(!is_int($head) and $head->getField() == $answer)
                        continue;
                    else
                        $result = array_merge($result, self::createAnswer($userManager->get($thread->getUser()), $answer, $thread, $postManager, $userManager, $result, false));
                }else{
                    $parent->addData(["tunnelv2"=>array_diff($parent->getData()["tunnelv2"], [$thread->getId()])]);
                    $postManager->update($parent);
                }
            }
        }
        return $result;
    }

    public static function answerRelay(Post $thread, $message, PostManager $pm, UserManager $um){
        global $hash;
        $dejavu = [];
        if(isset($thread->getData()["tunnelv2"])){
            foreach($thread->getData()["tunnelv2"] as $t){
                $tunnel = $pm->get($t);
                if(in_array($thread->getId(), $tunnel->getData()["input"]))
                    self::createAnswer($um->get($tunnel->getUser()), $hash->get($thread->getId())." -> ".$message, $tunnel, $pm, $um);
                else{
                    $thread->addData(["tunnelv2"=>array_diff($thread->getData()["tunnelv2"], [$t])]);
                    $pm->update($thread);
                }
            }
        }
    }

    public static function subscribe(User $user, Post $post, PostManager $manager, UserManager $um){
        if(!$post->getData()['open'])
            return 5;
        $state = ThreadControl::subscribe(ThreadControl::getInfluence($post), $user, $post, $manager, $um);
        if($state == 0 and ThreadControl::isAlert($post)){
            $corps = new SubscribeMailWidget($user, $post, $manager);
            $mail = new WrapperMail($post->getData()['title'], $user, $corps);
            $mail->send();
        }
        return $state;
    }

    public static function unsubscribe(User $user, Post $post, PostManager $manager, UserManager $um){
        $state = ThreadControl::unsubscribe(ThreadControl::getInfluence($post), $user, $post, $manager, $um);
        if($state == 0){

        }
        return $state;
    }

    public static function hasSubscribe(User $user, Post $post){
        if(in_array($user->getId(), $post->getData()['subscribers']))
            return 1;
        return 0;
    }

    public static function updateSubscriber(Post $post, PostManager $postManager, UserManager $userManager){
        foreach($post->getData()['subscribers'] as $subscriber){
            $user = ThreadControl::getUser($subscriber, $userManager);
            $corps = new HistoryMailWidget($user, $post, $postManager);
            $mail = new WrapperMail($post->getData()['title'], $user, $corps);
            $mail->send();
        }
        return 0;
    }

    public static function isNotify(Post $post){
        return isset($post->getData()["notify"]) and $post->getData()["notify"];
    }
}
