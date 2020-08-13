<?php
class ProfileOW extends OptionWidget{
    private $_user;

    public function __construct(User $user){
        parent::__construct("Profile", $user->getPseudo(), "#2A2A2A", ['home', 'page', 'setting', 'logout']);
        $this->_user = $user;
    }

    public function screen(Manager $manager){
        $list = [
            "Groooooooowth !",
            "Not Minecraft but kind of",
            "Liberté,  Égalité, Fraternité",
            "You are incredible 🙌🏿",
            "✊🏿✊🏿✊🏿✊🏿✊🏿✊🏿✊🏿",
            "Fraternité, Justice, Travail",
            "¿A que hora comemos?",
            "<span class='vert'>あなたに長い人生</span>"
        ];
        if(!isset($this->_user->getData()['instagram']))
            array_push($list, "You can link your instagram profile in your profile setting");
        if(!isset($this->_user->getData()['facebook']))
            array_push($list, "You can link your facebook page in your profile setting");
        if(!isset($this->_user->getData()['snapchat']))
            array_push($list, "You can link your snapchat profile in your profile setting");
        if(!isset($this->_user->getData()['linkedin']))
            array_push($list, "You can link your linkedin profile in your profile setting");
        shuffle($list);
        return $list[1];
    }
}
