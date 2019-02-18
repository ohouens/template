<?php
class ThreadLock extends Overlay{
    private $_list;
    private $_cursor;
    private $_current;

    public function __construct(Post $post, ThreadManager $manager){
        parent::__construct($post, $manager, "");
        $this->_list = $manager->getThreadChildren($post);
        $this->_list = $manager->listFormatFilter($this->_list, [
            constant::FORMAT_VOTE,
            Constant::FORMAT_BARRIER,
            Constant::FORMAT_REQUEST,
            Constant::FORMAT_CONTRACT
        ]);
        if(sizeof($this->_list) == 0){
            trigger_error("This post does not have lock children", E_USER_ERROR);
            return;
        }
        $this->_cursor = 0;
        $this->_current = $this->_list[$this->_cursor];
    }

    public function getCurrent(){
        return $this->_current;
    }

    public function next(){
        if($this->_cursor == sizeof($this->_list)-1){
            trigger_error("End of list" , E_USER_WARNING);
            return;
        }else
            $this->_cursor += 1;
        $this->_current = $this->list[$this->_cursor];
    }

    public function previous(){
        if($this->_cursor == 0){
            trigger_error("End of list" , E_USER_WARNING);
            return;
        }else
            $this->_cursor -= 1;
        $this->_current = $this->list[$this->_cursor];
    }

    public function last(){
        $this->_cursor = sizeof($this->_list)-1;
        $this->_current = $this->_list[$this->_cursor];
    }

    public function first(){
        $this->_cursor = 0;
        $this->_current = $this->_list[$this->_cursor];
    }

    public function getList(){
        return $this->_list;
    }
}
