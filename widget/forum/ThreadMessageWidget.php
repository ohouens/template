<?php
class ThreadMessageWidget extends Widget{
    private $_post;

    public function __construct(Post $post, PDO $db){
        $this->_post = $post;
        parent::__construct(
            "",
            '<div num="'.$post->getId().'" class="answer'.$this->cote().'">
                <p class="user">
                    '.UserManager::getPseudo($post->getUser(), $db).'
                </p>
                <p class="message">
                    '.$post->getField().'
                </p>
            </div>',
            "",
            "",
            false,
            false
        );
        $this->build();
    }

    private function cote(){
        if(!isset($_SESSION['id']))
            return ' left';
        if($_SESSION['id'] == $this->_post->getUser())
            return ' right';
        return ' left';
    }
}
