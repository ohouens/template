<?php
class ForumControl{
    public static function getObject(Post $post, Manager $manager){

    }

    public static function read(Post $post, PostManager $postManager, UserManager $userManager){
        $result = "";
        $list = $postManager->getList();
        $final = [];
        foreach($list as $answer){
            if($answer->getType() == Constant::THREAD_ANSWER and $answer->getData()['parent'] == $post->getId())
                array_push($final, $answer);
        }
        foreach($final as $inter){
            $result .=
            '<div class="answer">
                <p class="pseudo">'.self::getAutor($inter->getUser(), $userManager)->getPseudo().'</p>
                <p class="text">'.$inter->getField().'</p>
                <p class="gris">'.ForumControl::getSeniority($inter).'</p>
            </div>';
        }
        return $result;
    }

    public static function getAutor($id, Manager $manager){
        return $manager->get($id);
    }

    public static function getSeniority(Post $post){
        $d1 = DateTime::createFromFormat('U',$post->getCreation());
        $d2 = new DateTime("now");
        $interval = $d1->diff($d2);
        if($interval->y > 1)
            return $interval->format("%y years ago");
        elseif($interval->m > 1)
            return $interval->format("%m months ago");
        elseif($interval->d > 1)
            return $interval->format("%d days ago");
        elseif($interval->i > 1)
            return $interval->format("%i minutes ago");
        else
            return "a moment moment ago";
    }

    public static function createAnswer(User $user, $answer, integer $parent, PostManager $manager){
        if(!preg_match("#^.{1,1000}$#", $answer))
            return 11;
        $post = new Post();
        $post->setUser($user->getId());
        $post->setField($answer);
        $post->setType(Constant::THREAD_ANSWER);
        $post->addData(["parent"=>$parent]);
        $manager->add($post);
        return 0;
    }
}
