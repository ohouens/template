<?php
class ThreadActionWidget extends Widget{
    protected $_post;

    public function __construct(Post $post, PDO $db){
        parent::__construct(
            "",
            '<div id="special_container"></div>',
            "",
            "action",
            "",
            false,
            true
        );
        $this->_post = $post;
        $this->build();
    }
}
