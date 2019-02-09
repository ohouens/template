<?php
class ProfileOW extends OptionWidget{
    public function __construct(User $user){
        parent::__construct("Profile", $user->getPseudo(), "#2A2A2A", ['home', 'page', 'setting', 'logout']);
    }

    public function screen(){
        return "bienvenue";
    }
}
