<?php
class StoreOW extends OptionWidget{
    public function __construct(User $user){
        $this->_hash = [
            "licence" => "store=detail&object=licence",
            "template" => "store=detail&object=template",
            "software" => "store=detail&object=software"
        ];
        parent::__construct("Store", "store", "#535F67", ['licence']);
    }

    public function screen(){
        return "Licence available for 50€";
    }
}
