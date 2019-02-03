<?php
class ThreadPost extends Overlay implements Subscription{

    public function  __construct(Post $post){
        parent::__construct($post, "subscribe");
    }

    public function subscribe($id){
        if(!is_int($id))
            return 402;
        if(key_exists($this->_dataName, $this->_raw->getData())){
            if(in_array($id, $this->_raw->getData()[$this->_dataName]))
                return 400;
            else
                array_push($this->_raw->getData()[$this->_dataName], $id);
        }else
            $this->_raw->addData(["subscribe" => [$id]]);
        return 0;
    }

    public function unsubscribe($id){
        if(!is_int($id))
            return 402;
        if(key_exists($this->_dataName, $this->_raw->getData())){
            if(in_array($id, $this->_raw->getData()[$this->_dataName])){
                $temoin = [$this->_dataName => array_diff($this->_raw->getData()[$this->_dataName], [$id])];
                $this->_raw->removeData($this->_dataName);
                $this->_raw->addData($temoin);
                return 0;
            }
        }else
            return 402;
    }
}
