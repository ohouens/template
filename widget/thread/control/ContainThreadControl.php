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
        $list = $manager->getList();
        foreach($list as $thread){
            if($thread->getUser() == $user->getId() or in_array($thread->getData()['followers'], $user->getId()))
		         $page .= self::construct($thread);
		}
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

    private static function construct(Post $post){
        $result = "";
        $class = "";
        switch($post->getType()){
            case Constant::THREAD_FORUM:
                $class = 'forum';
                $result = '
                <img class="large" src="media/forum/cover/'.$post->getField().'"/>
                <h3>'.$post->getData()['title'].'</h3>';
                break;
            case Constant::THREAD_FLUX:
                $class = 'flux';
                $result = '
                <h3>'.$post->getData()['title'].'</h3>
                <p>'.$post->getField().'</p>';
                break;
            case Constant::THREAD_TICKETING:
                $class = 'ticketing';
                $result = '
                <h3>'.$post->getData()['title'].'</h3>
                <p>'.date('d\t\h <\b\r/>F <\b\r/>Y', strtotime($post->getField())).'</p>';
                break;
            default:
                break;
        }
        return '
        <div class="alignement thread '.$class.'" num="'.$post->getId().'">
        '.$result.'
        </div>';
        return $result;
    }
}