<?php
class ThreadAction extends Overlay{
    private $_list;

    public function __construct(Post $post, ThreadManager $manager){
        parent::__construct($post, $manager, "");
        $this->_list = $manager->getThreadChildren($post);
        $this->_list = $manager->listFormatFilter($this->_list, [
            constant::FORMAT_VOTE,
            Constant::FORMAT_BARRIER,
            Constant::FORMAT_REQUEST,
            Constant::FORMAT_CONTRACT
        ]);
    }

    public function getList(){
        return $this->_list;
    }
}
