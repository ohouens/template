<?php
class ThreadStatutWidget extends Widget{
    protected $_post;

    public function __construct(Post $post, PDO $db){
        parent::__construct(
            "",
            '<div id="statut_container">
                <div id="button_container">
                    <button id="subscribe" class="buttonB" num="'.$post->getId().'" valide="0">Subscribe</button>
                </div>
                <div id="primar_container">
                    <span class="synchro">By: </span>'.UserManager::getPseudo($post->getUser(), $db).'<br/>
                    <span class="synchro">Since: </span>'.date('d/m/Y', $post->getCreation()).'<br/>
                </div>
                <div id="users_container">
                    <p id="users_stats"></p>
                    <p id="list_users"></p>
                </div>
            </div>',
            "",
            "statut",
            "",
            false,
            true
        );
        $this->_post = $post;
        $this->build();
    }
}
