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

    public static function changePassword(){

    }

    public static function changeEmail(){

    }
}
