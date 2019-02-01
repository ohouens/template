<?php
class ThreadPost implements Subscription{
    private $_post;

    public function  __construct(Post $post){
        $this->_post = $post;
    }

    public function subscribe($id){

    }

    public function unsubscribe($id){

    }
}
