<?php
class Katalogi{
    public static function distance($lat1, $long1, $lat2, $long2){
    	$result = 6367445*acos(sin(deg2rad($lat1))*sin(deg2rad($lat2))+cos(deg2rad($lat1))*cos(deg2rad($lat2))*cos(deg2rad($long1-$long2)));
    	//echo 'lat1 '.$lat1.'<br/>long1 '.$long1.'<br/>lat2 '.$lat2.'<br/>long2 '.$long2.'<br/>distance: '.$result.'m<br/><br/>';
    	return $result;
    }

    public static function getSubType(Post $post){
        if($post->getType() != 5)
            return "thread";
        switch($post->getField()){
            case 0:
                return "lecture";
            case 1:
                return "gps";
            case 2:
                return "code";
            case 3:
                return "link";
            default:
                return "undefined";
        }
    }

    public static function indexThreadFromStart(User $user, UserManager $um, PostManager $pm){
        global $hash;
        $tab = [];
        foreach($pm->getList() as $thread){
            if($thread->getUser() == $user->getId() and in_array($thread->getType(), [Constant::THREAD_POSTER])){
                array_push($tab, $thread->getId());
                $hash->add($thread->getId());
            }
        }
        $user->addData(["posters"=>$tab]);
        $um->update($user);
    }

    public static function indexThread($threadId, User $user, Post $post, UserManager $um, PostManager $pm){
        global $hash;
        $thread = $pm->get($threadId);
        if($post->getUser() != $thread->getUser() or $post->getData()['title'] != $thread->getData()['title'])
            return self::indexThreadFromStart($user, $um, $pm);
        $tab = $user->getData()["posters"];
        array_push($tab, $threadId);
        $user->addData(["posters"=>$tab]);
        $um->update($user);
        $hash->add($thread->getId());
    }

    public static function createPoster(User $user, $title, $cover, $desc, $subtype, $extra, UserManager $um, PostManager $pm, PointManager $lm, $path=""){
        global $hash;
        $limit = CreateThreadControl::hasLimit($user,$lm);
        if($limit != 0)
            return $limit;
        if(!preg_match(Constant::REGEX_FORMAT_TITLE, $title))
            return Constant::ERROR_CODE_THREAD_TITLE;
        if(!preg_match("#^.{1,250}$#s", $desc))
            return Constant::ERROR_CODE_THREAD_LENGTH;
        $post = new Post();
        switch($subtype){
            case "1": //gps
                $frag = explode(" || ", $extra);
                if($frag[0] == null or $frag[0] == "")
                    return 295;
                if(!preg_match("/^-?[0-9]{1,7}\.[0-9]+$/", $frag[1]) or !preg_match("/^-?[0-9]{1,7}\.[0-9]+$/", $frag[2]))
                    return 296;
                $post->addData(["address"=>$frag[0]]);
                $post->addData(["lat"=>$frag[1]]);
                $post->addData(["long"=>$frag[2]]);
                break;
            case "2": //code
                if(!preg_match("/^.{1,20}$/", $extra))
                    return Constant::ERROR_CODE_USER_WRONG;
                $post->addData(["code"=>$extra]);
                break;
            case "3": //link
                if(!preg_match("/^((https?):\/\/(w{3}\.)?)?[a-z]{1,255}\.[a-z]{2,15}\/?/", $extra))
                    return Constant::ERROR_CODE_USER_WRONG;
                $post->addData(["link"=>$extra]);
                break;
        }
        $extension = substr(strrchr($cover['name'],'.'),1);
        $rename = $user->getID().achage(42).'.'.$extension;
        $dest = $path.'media/forum/cover/'.$rename;
        $verif = upload($cover, $dest, 1048576, ["png", "jpg", "jpeg"]);
        switch($verif){
            case 0:
                return 15;
            case 1:
                $post->setUser($user->getId());
                $post->setField($subtype);
                $post->setType(Constant::THREAD_POSTER);
                $post->addData(["title"=>$title]);
                $post->addData(["writers"=>[]]);
                $post->addData(["viewers"=>[]]);
                $post->addData(["open"=>true]);
                $post->addData(["cover"=>$rename]);
                $post->addData(["desc"=>$desc]);
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

    public static function catalogue(PostManager $pm, $lat=0, $long=0){
        global $hash;
    	$tab=[];
    	$i=0;
    	foreach(array_reverse($pm->getListOfType(Constant::THREAD_POSTER)) as $poster){
            if($i >= 30) break;
			//creation de la demande
			$tab[$i]["num"] = $hash->get($poster->getId());
            $tab[$i]["type"] = $poster->getField();
			$tab[$i]["titre"] = $poster->getData()["title"];
			$tab[$i]["description"] = $poster->getData()["desc"];
			$tab[$i]["image"] = $poster->getData()["cover"];
            switch($poster->getField()){
                case 1:
                    $tab[$i]["address"] = $poster->getData()["address"];
                    $tab[$i]["lat"] = $poster->getData()["lat"];
                    $tab[$i]["long"] = $poster->getData()["long"];
                    break;
                case 2:
                    $tab[$i]["extra"] = $poster->getData()["code"];
                    break;
                case 3:
                    $tab[$i]["extra"] = $poster->getData()["link"];
                    break;
            }
            $i++;
    	}
    	return json_encode($tab);
    }

    public static function mapsAPI($address){
        $curl = curl_init();
        $send = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBiMGhNTqf79uOzcdYzPwujsczTHZaU_SY&address=".$address;
    	$opts = [
    		CURLOPT_SSL_VERIFYPEER => false,
    		CURLOPT_URL            => $send,
    		CURLOPT_RETURNTRANSFER => true,
    	];
    	curl_setopt_array($curl, $opts);
    	$content = curl_exec($curl);
        return $content;
    	curl_close($curl);
    }
}
