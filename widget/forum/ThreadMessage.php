<?php
class ThreadMessage extends Overlay{
    private $_list;

    public function __construct(Post $post, ThreadManager $manager){
        parent::__construct($post, $manager, "");
        $this->_list = $manager->getThreadChildren($post);
        $this->_list = $manager->listFormatFilter($this->_list, [Constant::FORMAT_SIMPLE]);
    }

    public function getCote($user, $num){
        if($user == $num)
            return 1;
        return 0;
    }

    public function getList(){
        return $this->_list;
    }
}
