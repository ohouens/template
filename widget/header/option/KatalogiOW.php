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
        return '<a href="https://play.google.com/store?hl=fr"><span class="gold">Check the app !</span></a>';
    }
}
