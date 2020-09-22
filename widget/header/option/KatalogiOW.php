<?php
class KatalogiOW extends OptionWidget{
    private $_user;

    public function __construct(User $user){
        $this->_hash = [
            "new" => "katalogi=creation",
            "settings" => "katalogi=settings"
        ];
        parent::__construct("Katalogi", "katalogi", "#ae1a4f", ["new", "settings"]);
        $this->_user = $user;
    }

    public function screen(Manager $manager){
        return '
        <span class="gold"style="vertical-align:middle;"">Check the app !</span>
        <a href="https://play.google.com/store?hl=fr"> <img src="style/icon/playstore.png" alt="playstore" style="height: 30px; vertical-align:middle;"></a>
        <img src="style/icon/ios.png" alt="ios" style="height: 30px; vertical-align:middle;">';
    }
}
