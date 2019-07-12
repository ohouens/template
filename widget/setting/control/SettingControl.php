<?php
class SettingControl{
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

    public static function changePassword($pass, User $user, Manager $manager){
        if($pass['old'] != $user->getPassword())
            return 31;
        if($pass['new'] != $pass['confirm'])
            return 32;
        $temoin = $user->setPassword($pass['confirm']);
        if(is_int($temoin))
            return $temoin;
        $manager->update($user);
        return 0;
    }

    public static function changeEmail($mail, User $user, Manager $manager){
        $list = $manager->getList();
        foreach($list as $member){
            if($mail == $member->getEmail())
                return 33;
        }
        $temoin = $user->setEmail($mail);
        if(is_int($temoin))
            return $temoin;
        $manager->update($user);
        return 0;
    }

    public static function changeGender($gender, User $user, Manager $manager){
        if(!preg_match("#^0|1$#", $gender))
            return Constant::ERROR_CODE_GENDER_FORMAT;
        $user->addData(["gender" => $gender]);
        $manager->update($user);
        return 0;
    }

    public static function changeCountry($country, User $user, Manager $manager){
        if(!preg_match("#^[a-z]{2}|[A-Z]{2}$#", $country))
            return Constant::ERROR_CODE_COUNTRY_FORMAT;
        $user->addData(["country" => $country]);
        $manager->update($user);
        return 0;
    }

    public static function changeBirth($birth, User $user, Manager $manager){
        if(!checkIsAValidDate($birth))
            return 16;
        $user->addData(["birth" => $birth]);
        $manager->update($user);
        return 0;
    }

    public static function changeSocial($social, User $user, Manager $manager){
        switch($social){
            case 'artist':
                break;
            case 'athlete':
                break;
            case 'student':
                break;
            case 'worker':
                break;
            default:
                return Constant::ERROR_CODE_SOCIAL_FORMAT;
        }
        $user->addData(["social" => $social]);
        $manager->update($user);
        return 0;
    }

    public static function changeHobby($hobby, User $user, Manager $manager){
        switch($hobby){
            case 'coit':
                break;
            case 'dance':
                break;
            case 'eat':
                break;
            case 'fashion':
                break;
            case 'fitness':
                break;
            case 'game':
                break;
            case 'music':
                break;
            case 'party':
                break;
            case 'read':
                break;
            case 'sleep':
                break;
            case 'think':
                break;
            case 'travel':
                break;
            default:
                return Constant::ERROR_CODE_HOBBY_FORMAT;
        }
        $user->addData(["hobby" => $hobby]);
        $manager->update($user);
        return 0;
    }

    public static function country(User $user){
        $data = $user->getData();
        if(isset($data['country']))
            return $data['country'];
        return "Statelessness";
    }
}
