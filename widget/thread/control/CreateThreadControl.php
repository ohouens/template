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
            default:
                $retour .= '';
                break;
        }
        return $retour;
    }

    public static function createFlux(User $user, $title, $intro, Manager $manager){
        if(!preg_match("#^([\w]+[?. ]?){2,77}$#", $title))
            return 10;
        if(!preg_match("#^.{1,1000}$#", $intro))
            return 11;
        $post = new Post();
        $post->setUser($user->getId());
        $post->setField($intro);
        $post->setType(1);
        $post->addData(["title"=>$title]);
        $manager->add($post);
        return 0;
    }

    public static function createForum(User $user, $title, $cover, Manager $manager, $path=""){
        if(!preg_match("#^([\w]+[?. ]?){2,77}$#", $title))
            return 10;
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
                $manager->add($post);
                return 0;
                break;
            case 701:
                return 12;
                break;
            case 702:
                return 13;
                break;
            case 703:
                return 14;
                break;
            default:
                break;
        }
    }

    public static function createTicketing(User $user, $title, $date, Manager $manager){
        if(!preg_match("#^([\w]+[?. ]?){2,77}$#", $title))
            return 10;
        if(!checkIsAValidDate($date))
            return 16;
        $post = new Post();
        $post->setUser($user->getId());
        $post->setField($date);
        $post->setType(2);
        $post->addData(["title"=>$title]);
        $manager->add($post);
        return 0;
    }
}
