<?php
class SettingControl{
    const SOCIAL = ["artist", "athlete", "cadre", "student", "worker"];
    const HOBBY = ["coit", "dance", "eat", "fashion", "fitness", "gaming", "music", "party", "read", "sleep", "think", "travel"];

    public static function reset(User $user, $data, UserManager $manager){
        foreach($data as $inter)
            $user->removeData($inter);
        $manager->update($user);
        return Constant::ERROR_CODE_OK;
    }

    public static function changePdp($cover, User $user, UserManager $manager){
        $rename = renameFile($cover, $user->getID().achage(28));
        switch(checkUpload($cover, $rename, 'media/user/pp/')){
            case 0:
                return 15;
            case 1:
                try{
                    $old = $user->getData()['pp'];
                    unlink('media/user/pp/'.$old);
                }finally{
                    $user->addData(["pp" => $rename]);
                    $manager->update($user);
                    return 0;
                }
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

    public static function changePassword($pass, User $user, UserManager $manager){
        if($pass['old'] != $user->getPassword())
            return 31;
        if($pass['new'] != $pass['confirm'])
            return 32;
        $temoin = $user->hashPassword($pass['confirm']);
        if(is_int($temoin))
            return $temoin;
        $manager->update($user);
        return Constant::ERROR_CODE_OK;
    }

    public static function changeInstagram($insta, User $user, UserManager $manager){
        if(!preg_match("/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{0,29}$/im", $insta))
            return Constant::ERROR_CODE_INSTAGRAM_FORMAT;
        $user->addData(["instagram"=>$insta]);
        $manager->update($user);
        return Constant::ERROR_CODE_OK;
    }

    public static function changeLinkedin($li, User $user, UserManager $manager){
        if(!preg_match("#^https://([a-z]{2,3}\.)?linkedin\.com/.*$#", $li))
            return Constant::ERROR_CODE_LINKEDIN_FORMAT;
        $user->addData(["linkedin"=>$li]);
        $manager->update($user);
        return Constant::ERROR_CODE_OK;
    }

    public static function changeEmail($mail, User $user, UserManager $manager){
        $list = $manager->getList();
        foreach($list as $member){
            if($mail == $member->getEmail())
                return 33;
        }
        $temoin = $user->setEmail($mail);
        if(is_int($temoin))
            return $temoin;
        $manager->update($user);
        return Constant::ERROR_CODE_OK;
    }

    public static function changeGender($gender, User $user, UserManager $manager){
        if(!preg_match("#^0|1$#", $gender))
            return Constant::ERROR_CODE_GENDER_FORMAT;
        $user->addData(["gender" => $gender]);
        $manager->update($user);
        return Constant::ERROR_CODE_OK;
    }

    public static function changeCountry($country, User $user, UserManager $manager){
        if(!preg_match("#^[a-z]{2}|[A-Z]{2}$#", $country))
            return Constant::ERROR_CODE_COUNTRY_FORMAT;
        $user->addData(["country" => $country]);
        $manager->update($user);
        return Constant::ERROR_CODE_OK;
    }

    public static function changeBirth($birth, User $user, UserManager $manager){
        if(!checkIsAValidDate($birth))
            return 16;
        $user->addData(["birth" => $birth]);
        $manager->update($user);
        return Constant::ERROR_CODE_OK;
    }

    public static function changeSocial($social, User $user, UserManager $manager){
        if(!in_array($social, self::SOCIAL))
            return Constant::ERROR_CODE_SOCIAL_FORMAT;
        $user->addData(["social" => $social]);
        $manager->update($user);
        return Constant::ERROR_CODE_OK;
    }

    public static function changeHobby($hobby, User $user, UserManager $manager){
        if(!in_array($hobby, self::HOBBY))
            return Constant::ERROR_CODE_HOBBY_FORMAT;
        $user->addData(["hobby" => $hobby]);
        $manager->update($user);
        return Constant::ERROR_CODE_OK;
    }

    public static function country(User $user){
        $data = $user->getData();
        if(isset($data['country']))
            return $data['country'];
        return "Statelessness";
    }

    public static function gender(User $user){
        switch($user->getData()['gender']){
            case 0:
                return 'girl';
            case 1:
                return 'boy';
            default:
                return '';
        }
    }

    public static function getInstagram(User $user){
        if(!isset($user->getData()['instagram']))
            return "";
        return $user->getData()['instagram'];
    }

    public static function instagram(User $user){
        if(!isset($user->getData()['instagram']))
            return "";
        return "https://instagram.com/".$user->getData()['instagram'];
    }

    public static function linkedin(User $user){
        if(!isset($user->getData()['linkedin']))
            return "";
        return $user->getData()['linkedin'];
    }

    public static function deleteAccount(User $user, $password, $token, UserManager $manager){
        if($user->getPassword() != $password)
            return Constant::ERROR_CODE_USER_PASSWORD;
        if($user->getToken() != $token)
            return Constant::ERROR_CODE_USER_TOKEN;
        $user->setActive(0);
        $manager->update($user);
        return Constant::ERROR_CODE_OK;
    }
}
