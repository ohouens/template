<?php
class ThreadOW extends OptionWidget{
    private $_user;

    public function __construct(User $user){
        $this->_hash = [
            "new" => "thread=none&creation",
            "settings" => "thread=none&settings"
        ];
        parent::__construct("Thread", "thread", "#35465E", ['new', 'setting']);
        $this->_user = $user;
    }

    public function screen(Manager $manager){
        global $hash;
        $list = $this->_user->getData()["following"];
        shuffle($list);
        foreach($list as $num){
            $thread = $manager->get($num);
            if(is_int($thread))
                continue;
            switch($thread->getType()){
                case Constant::THREAD_FLUX:
                    if(isset($this->_user->getData()["number"][$thread->getId()])){
                        if($this->_user->getData()["number"][$thread->getId()] < $thread->getData()["head"])
                            return '<a href="index.php?thread='.$hash->get($thread->getId()).'">'.ucfirst(ThreadControl::getType($thread)).' "'.$thread->getData()["title"].'": <span class="vert">New messages !</span></a>';
                    }
                    break;
                case Constant::THREAD_FORUM:
                    if(isset($this->_user->getData()["number"][$thread->getId()])){
                        if($this->_user->getData()["number"][$thread->getId()] < $thread->getData()["head"])
                            return '<a href="index.php?thread='.$hash->get($thread->getId()).'">'.ucfirst(ThreadControl::getType($thread)).' "'.$thread->getData()["title"].'": <span class="vert">New messages !</span></a>';
                    }
                    break;
                case Constant::THREAD_TICKETING:
                    if(isset($this->_user->getData()["number"][$thread->getId()])){
                        if($this->_user->getData()["number"][$thread->getId()] < count($thread->getData()[ThreadControl::getInfluence($thread)]))
                            return '<a href="index.php?thread='.$hash->get($thread->getId()).'">'.ucfirst(ThreadControl::getType($thread)).' "'.$thread->getData()["title"].'": <span class="vert">New members !</span></a>';
                    }
                    break;
                case Constant::THREAD_LIST:
                    if(isset($this->_user->getData()["number"][$thread->getId()])){
                        if($this->_user->getData()["number"][$thread->getId()] < count($thread->getData()["list"]))
                            return '<a href="index.php?thread='.$hash->get($thread->getId()).'">'.ucfirst(ThreadControl::getType($thread)).' "'.$thread->getData()["title"].'": <span class="vert">New threads !</span></a>';
                    }
                    break;
                default:
                    break;
            }
        }
        return "Nothing new for the moment...";
    }
}
