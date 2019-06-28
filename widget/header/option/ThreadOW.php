<?php
class ThreadOW extends OptionWidget{
    public function __construct(User $user){
        parent::__construct("Thread", "thread", "#35465E", ['new', 'list']);
    }

    public function screen(){
        return "First Post";
    }
}
