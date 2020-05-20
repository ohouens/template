<?php
class ContainThreadControl{
    public static function getObject(User $user, Manager $manager, $number){
        if($number == "firstPage")
            return self::page($user, $manager, 0);
        elseif(preg_match("#^[0-9]+$#"))
            return self::page($user, $manager, $number);
        else
            return "";
    }

    private static function page(User $user, Manager $manager, $number){
        $page = "";
        $list = $user->getData()["following"];
        rsort($list);
        foreach($list as $num){
            $thread = $manager->get($num);
            if(!is_int($thread))
                $page .= self::construct($user, $thread, $manager);
        }
        if($page == "")
            return
            '<div id="noThread" class="children square">
                <div class="center">
                    <p>
                        <img src="style/icon/void.png" alt="that\'s sad" style="width:250px;"/><br/>
                        You have subscribed to 0 thread<br/>
                        You can check associations and commerces near you to subscribe to their content
                    </p>
                </div>
            </div>';
        return $page;
    }

    public static function last(User $user, PostManager $manager){
        $list = $manager->getList();
        foreach (array_reverse($list) as $inter) {
            if($inter->getUser() == $user->getId())
                return $inter;
        }
        return new User();
    }

    public static function construct(User $user, Post $post, PostManager $manager){
        global $hash;
        $result = "";
        $class = "";
        $news = "";
        switch($post->getType()){
            case Constant::THREAD_FORUM:
                if(isset($user->getData()["number"][$post->getId()])){
                    if($user->getData()["number"][$post->getId()] < $post->getData()["number"])
                        $news = '<p class="new">NEW MESSAGES</p>';
                }
                $class = 'forum';
                $result = '
                <img class="large" src="media/forum/cover/'.$post->getField().'"/>'.$news.'
                <h3>'.$post->getData()['title'].'</h3>';
                break;
            case Constant::THREAD_FLUX:
                if(isset($user->getData()["number"][$post->getId()])){
                    if($user->getData()["number"][$post->getId()] < $post->getData()["number"])
                        $news = '<p class="new">NEW MESSAGES</p>';
                }
                $alert = "";
                if(ThreadControl::isAlert($post))
                    $alert = ' <img src="style/icon/notification.png" alt="alert" class="alert"/>';
                $notify = "";
                if(FluxControl::isNotify($post))
                    $notify = ' <img src="style/icon/notify.png" alt="notify" class="notify"/>';
                $class = 'flux';
                $result = '
                <h3>'.$post->getData()['title'].$alert.$notify.'</h3>'.$news.'
                <p>'.$post->getField().'</p>';
                break;
            case Constant::THREAD_TICKETING:
                $alert = "";
                if(ThreadControl::isAlert($post))
                    $alert = ' <img src="style/icon/notification.png" alt="alert" class="alert"/>';
                $class = 'ticketing';
                $result = '
                <h3>'.$post->getData()['title'].$alert.'</h3>
                <p>'.date('d\t\h <\b\r/>F <\b\r/>Y', strtotime($post->getField())).'</p>';
                break;
            case Constant::THREAD_LIST:
                if(isset($user->getData()["number"][$post->getId()])){
                    if($user->getData()["number"][$post->getId()] < $post->getData()["number"])
                        $news = '<div class="new">NEW THREADS</div>';
                }
                $i = 0;
                $j = 0;
                $list = $post->getData()['list'];
                $cover = "";
                $img = [];
                while($i < count($list) and $j < 4){
                    $thread = $manager->get($list[$i]);
                    if(!is_int($thread) and $thread->getType() == Constant::THREAD_FORUM){
                        array_push($img,$thread->getField());
                        $j++;
                    }
                    $i++;
                }
                switch($j){
                    case 1:
                        $cover='<div class="cover" style="background-image: url(media/forum/cover/'.$img[0].'); width: 100%; height: 300px;"></div>';
                        break;
                    case 2:
                        $cover = '<div class="cover" style="background-image: url(media/forum/cover/'.$img[0].');"></div><!--
                        --><div class="cover" style="background-image: url(media/forum/cover/'.$img[1].');"></div>';
                        break;
                    case 4:
                        $cover.='<div class="cover" style="background-image: url(media/forum/cover/'.$img[0].');"></div><!--
                        --><div class="cover" style="background-image: url(media/forum/cover/'.$img[1].');"></div><!--
                        --><div class="cover" style="background-image: url(media/forum/cover/'.$img[2].');"></div><!--
                        --><div class="cover" style="background-image: url(media/forum/cover/'.$img[3].');"></div>';
                        break;

                }
                $class = 'list';
                $result = '
                '.$cover.''.$news.'
                <h3>'.$post->getData()['title'].'[ ]</h3>';
                break;
            default:
                break;
        }
        return '
        <a class="alignement thread '.$class.'" href="index.php?thread='.$hash->get($post->getId()).'">
        '.$result.'
        </a>';
        return $result;
    }
}
