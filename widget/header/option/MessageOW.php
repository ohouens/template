<?php
class MessageOW extends OptionWidget{
    public function __construct(User $user){
        parent::__construct("Message", '0 new Message', "#00B5AA", ['direct', 'chat']);
    }

    public function screen(){
        return "On sera bientôt riche";
    }
}
