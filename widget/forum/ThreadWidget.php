<?php
class ThreadWidget extends Widget{
    protected $_post;

    public function __construct(Post $post){
        parent::__construct(
            "",
            '<div id="chatReading" num="'.$post->getId().'"></div>
                <div id="chatWriting" num="'.$post->getId().'">
                    <form method="post" action="index.php?thread='.$post->getId().'&amp;request=1">
                        <input type="text" class="alignement grand" name="answer"/><!--
                        --><input class="alignement grand buttonB" type="submit" value="send"/>
                    </form>
                </div>',
            "",
            "thread_".$post->getId(),
            "",
            false,
            false
        );
        $this->_post = $post;
        $this->build();
    }
}
