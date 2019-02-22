<?php
class ThreadLockWidget extends Widget{
    protected $_post;

    public function __construct(Post $post){
        parent::__construct(
            "",
            '<div id="action_container" num="'.$post->getId().'" class="plein">
                <div class="grand rectangle previous">
                    <div class="center">
                        <img src="style/icon/previous.png" alt="previous"/>
                    </div>
                </div><!--
                --><div id="action_current" class="grand rectangle">
                    <div class="center">

                    </div>
                </div><!--
                --><div class="grand rectangle next">
                    <div class="center">
                        <img src="style/icon/next.png" alt="next"/>
                    </div>
                </div>
            </div>',
            "",
            "action",
            "",
            false,
            false
        );
        $this->_post = $post;
        $this->build();
    }
}
