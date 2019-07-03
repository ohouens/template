<?php
class DisplayThreadControl{
    public static function getObject(Manager $manager, $number){
        if($number == "firstPage")
            return self::page($manager, 0);
        elseif(preg_match("#^[0-9]+$#"))
            return self::page($manager, $number);
        else
            return "";
    }

    private static function page(Manager $manager, $number){
        $page = "";
        $list = $manager->getList();
        foreach($list as $thread){
			$page .= self::construct($thread);
		}
        return $page;
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
                <p>'.$post->getField().'</p>';
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
