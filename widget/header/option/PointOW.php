<?php
class PointOW extends OptionWidget{
    public function __construct(User $user){
        parent::__construct("Point", "0P", "#2A2A2A", ['buy', 'transfert', 'code']);
    }

    public function screen(){
        return "Tres tres pauvre";
    }
}
