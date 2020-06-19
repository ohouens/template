<?php
class LicenceControl{
    public static function isValide(User $user, PointManager $manager){
        if(!isset($user->getData()['licence']))
            return false;
        return true;
    }

    public static function create(User $user, $object, $price, $token, $buyerID, UserManager $userManager, PointManager $pointManager){
        $country = "FR";
        if(isset($user->getData()['country']))
            $country = $user->getData()['country'];
        $plaque = strval(count($pointManager->getList()) + 10000001);

        $licence = new Point();
        $licence->setCountry($country);
        $licence->setOrigin("1AA".$plaque);
        $licence->setCreator("1AA");
        $licence->compressName();

        $licence->addData([
            "object"=>$object,
            "amount"=>$price,
            "token"=>$token,
            "id"=>$buyerID
        ]);
        $user->addData(["licence"=>$licence->getName()]);

        try {
            $pointManager->add($licence);
            $userManager->update($user);
        } catch (Exception $e) {
            echo $e->getMessage();
            return 1;
        }

        return 0;
    }
}
