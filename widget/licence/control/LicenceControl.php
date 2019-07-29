<?php
class LicenceControl{
    public static function isValide(User $user, PointManager $manager){
        if(!isset($user->getData()['licence']))
            return false;
        return true;
    }
}
