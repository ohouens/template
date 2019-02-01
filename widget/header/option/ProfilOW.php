<?php
class ProfilOW extends OptionWidget{
    public function __construct(User $user){
        parent::__construct("Profil", $user->getPseudo(), "#2A2A2A", ['home', 'setting', 'logout']);
    }

    public function screen(){
        return "bienvenue";
    }
}
