<?php
class DisplayThreadWidget extends Widget{
    private $_post;

    public function __construct(Post $post){
        parent::__construct(
            "",
            $this->subConstruct($post),
            "",
            $post-getId(),
            "",
            false,
            false
        );
        $this->build();
        $this->_post = $post;
    }

    private function subConstruct(Post $post){
        $result = "";
        
        return $result;
    }
}
