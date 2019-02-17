<?php
class ThreadActionWidget extends Widget{
    protected $_post;

    public function __construct(Post $post, PDO $db){
        parent::__construct(
            "",
            '<div id="action_container" num="'.$post->getId().'">
                <div class="alignement previous"></div><!--
                --><div id="current_action" class="alignement">

                </div><!--
                --><div class="alignement next"></div>
            </div>',
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
