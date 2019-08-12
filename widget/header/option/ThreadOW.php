<?php
class ThreadOW extends OptionWidget{
    private $_user;

    public function __construct(User $user){
        $this->_hash = [
            "new" => "thread=none&creation",
            "list" => "thread=none&list"
        ];
        parent::__construct("Thread", "thread", "#35465E", ['new', 'list']);
        $this->_user = $user;
    }

    public function screen(Manager $manager){
        $list = array_reverse($manager->getList());
        foreach($list as $thread){
            $numba = 0;
            if(in_array($this->_user, $thread->getData()[ThreadControl::getInfluence($thread)]))
                return ucfirst(ThreadControl::getType($thread)).' "'.$thread->getData()["title"].'": '.$numba." new message";
        }
        return "nothing new";
    }
}
