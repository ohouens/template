<?php
class ThreadOW extends OptionWidget{
    public function __construct(User $user){
        $this->_hash = [
            "new" => "thread=none&creation",
            "list" => "thread=none&list"
        ];
        parent::__construct("Thread", "thread", "#35465E", ['new', 'list']);
    }

    public function screen(){
        return "First Post";
    }
}
