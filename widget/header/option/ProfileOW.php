<?php
class ProfileOW extends OptionWidget{
    private $_user;

    public function __construct(User $user){
        parent::__construct("Profile", $user->getPseudo(), "#2A2A2A", ['home', 'page', 'setting', 'logout']);
        $this->_user = $user;
    }

    public function screen(Manager $manager){
        if(isset($this->_user->getData()['instagram']))
            return "You can copy the link of a thread by clicking on the qr code";
        return "Now you can link your instagram profile in your profile setting";
    }
}
