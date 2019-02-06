<?php
class ThreadPost extends Overlay implements Subscription{

    public function  __construct(Post $post, PostManager $manager){
        parent::__construct($post, $manager, "subscribe");
    }

    public function subscribe($id){
        if(!is_int($id))
            return 402;
        if(key_exists($this->_dataName, $this->_raw->getData())){
            if(in_array($id, $this->_raw->getData()[$this->_dataName]))
                return 400;
            else{
                $temoin = $this->_raw->getData()[$this->_dataName];
                $this->_raw->removeData($this->_dataName);
                array_push($temoin, $id);
                $this->_raw->addData([$this->_dataName => $temoin]);
            }
        }else
            $this->_raw->addData(["subscribe" => [$id]]);
        $this->_raw->compressTab();
        $this->_manager->update($this->_raw);
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
                $this->_raw->compressTab();
                $this->_manager->update($this->_raw);
                return  0;
            }
        }else
            return 402;
    }

    public function hasSubscribe($id){
        if(!key_exists("subscribe", $this->_raw->getData()))
            return false;
        return in_array($id, $this->_raw->getData()['subscribe']);
    }
}
