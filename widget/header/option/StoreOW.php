<?php
class StoreOW extends OptionWidget{
    public function __construct(User $user){
        parent::__construct("Store", "store", "#535F67", ['Licence', 'template', 'software']);
    }

    public function screen(){
        return "Licence available for 50€";
    }
}
