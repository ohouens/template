<?php
class CreateThreadWidget extends Widget{
    private $_user;

    public function __construct(User $user){
        parent::__construct(
            "",
            $this->subConstruct($user),
            "",
            "threadCreation",
            "",
            false,
            false
        );
        $this->_user = $user;
        $this->build();
    }

    private function subConstruct(User $user){
        return
        '<div id="createThread">
            <h1>New Thread</h1>
            <div class="large select">
                <span action="flux">Flux</span><!--
                --><span action="forum">Forum</span><!--
                --><span action="ticketing">Ticketing</span><!--
                --><span action="list">List</span>
            </div>
            <form enctype="multipart/form-data" method="post" action="index.php?thread&request=1"></form>
            <button id="submit" class="button space">Create</button>
            <div id="erreurCreate" class="erreur"></div>
        </div>
        ';
    }
}
