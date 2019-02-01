<?php
class CoverageWidget extends Widget{
    protected $_post;

    public function __construct(Post $post){
        parent::__construct($post->getField(), "", "read", $post->getId(), $post->getVar()['background'], true, true);
        $this->_post = $post;
		$this->setBackground("url('media/".$this->getBackground()."')");
        $this->build();
    }

    public function getPost(){return $this->_post;}

    public function setPost(Post $post){
        $this->_post = $post;
        $this->setTitle($post->getField());
        $this->setId($post->getId());
        $this->setBackground($post->getVar()['background']);
    }
}
