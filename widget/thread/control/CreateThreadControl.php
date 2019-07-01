<?php
class createThreadControl{
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
                <input type="file" name="cover" accept="image/x-png,image/jpeg" style="display: none"/>
                <img class="large" src="style/upload_image.png" alt="preview"/><br/>
                <input class="input  noBorder" type="text" name="title" placeholder="Title"/>';
                break;
            case 'ticketing':
                $retour .=
                '<input type="hidden" name="origin" value="ticketing"/>
                <input class="input large" type="text" name="title" placeholder="Title"/>
                <input type="date" name="when" class="input"/>';
                break;
            default:
                $retour .= '';
                break;
        }
        return $retour;
    }

    public static function createFlux(User $user, $title, $intro, $db){
        if(!preg_match("#^([\w]+[?. ]?){2,77}$#", $title))
            return 10;
        if(!preg_match("#^.{1,300}$#", $intro))
            return 11;
        $post = new Post();
        $post->setUser($user->getId());
        $post->setField($intro);
        $post->setType(1);
        $post->addData(["title"=>$title]);
        $manager = new PostManager($db);
        $manager->add($post);
        return 0;
    }

    public static function createForum(User $user, $title, $cover, $db){
        return 0;
    }

    public static function createTicketing(User $user, $title, $date, $db){
        return 0;
    }
}
