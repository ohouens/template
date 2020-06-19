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
        global $hash;
        $list = $this->_user->getData()["following"];
        shuffle($list);
        foreach($list as $num){
            $thread = $manager->get($num);
            switch($thread->getType()){
                case Constant::THREAD_FLUX:
                    if(isset($this->_user->getData()["number"][$thread->getId()])){
                        if($this->_user->getData()["number"][$thread->getId()] < $thread->getData()["head"])
                            return '<a href="index.php?thread='.$hash->get($thread->getId()).'">'.ucfirst(ThreadControl::getType($thread)).' "'.$thread->getData()["title"].'":  New messages !</a>';
                    }
                    break;
                case Constant::THREAD_FORUM:
                    if(isset($this->_user->getData()["number"][$thread->getId()])){
                        if($this->_user->getData()["number"][$thread->getId()] < $thread->getData()["head"])
                            return '<a href="index.php?thread='.$hash->get($thread->getId()).'">'.ucfirst(ThreadControl::getType($thread)).' "'.$thread->getData()["title"].'": New messages !</a>';
                    }
                    break;
                case Constant::THREAD_TICKETING:
                    if(isset($this->_user->getData()["number"][$thread->getId()])){
                        if($this->_user->getData()["number"][$thread->getId()] < count($thread->getData()[ThreadControl::getInfluence($thread)]))
                            return '<a href="index.php?thread='.$hash->get($thread->getId()).'">'.ucfirst(ThreadControl::getType($thread)).' "'.$thread->getData()["title"].'": New members !</a>';
                    }
                    break;
                case Constant::THREAD_LIST:
                    if(isset($this->_user->getData()["number"][$thread->getId()])){
                        if($this->_user->getData()["number"][$thread->getId()] < count($thread->getData()["list"]))
                            return '<a href="index.php?thread='.$hash->get($thread->getId()).'">'.ucfirst(ThreadControl::getType($thread)).' "'.$thread->getData()["title"].'": New threads !</a>';
                    }
                    break;
                default:
                    break;
            }
        }
        return "T_T Nothing new.. go get some friends T_T";
    }
}
