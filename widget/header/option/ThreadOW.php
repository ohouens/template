<?php
class ThreadOW extends OptionWidget{
    public function __construct(User $user){
        parent::__construct("Thread", "thread", "#2A2A2A", ['new', 'list']);
    }

    public function screen(){
        return "First Post";
    }
}
