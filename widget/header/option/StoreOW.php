<?php
class StoreOW extends OptionWidget{
    private $_user;

    public function __construct(User $user){
        $this->_hash = [
            "licence" => "store=detail&object=licence",
            "template" => "store=detail&object=template",
            "software" => "store=detail&object=software"
        ];
        parent::__construct("Store", "store", "#535F67", ['licence']);
        $this->_user = $user;
    }

    public function screen(Manager $manager){
        if(LicenceControl::isValide($this->_user, $manager))
            return "Licence: ".$this->_user->getData()['licence'];
        return "Licence available for 50€";
    }
}
