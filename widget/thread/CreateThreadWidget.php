<?php
class CreateThreadWidget extends Widget{
    private $_user;

    public function __construct(User $user, PointManager $lm, PostManager $pm){
        parent::__construct(
            "",
            $this->subConstruct($user, $lm, $pm),
            "",
            "threadCreation",
            "",
            false,
            false
        );
        $this->_user = $user;
        $this->build();
    }

    private function subConstruct(User $user, PointManager $lm, PostManager $pm){
        $chat = "";
        $list = "";
        if(LicenceControl::isValide($user, $lm)){
            $chat = '<span action="forum">Chat</span>';
            $list = '<span action="list">List</span>';
        }
        return
        '<div id="createThread">
            <h1>New Thread</h1>
            <div class="large select">
                <span action="flux">Flux</span><!--
                -->'.$chat.'<!--
                --><span action="ticketing">Register</span><!--
                -->'.$list.'
            </div>
            <form enctype="multipart/form-data" method="post" action="index.php?thread&request=1"></form>
            <button id="submit" class="button space">Create</button>
            <div id="erreurCreate" class="erreur"></div>
            <div id="takenSlots">Taken slots: '.ThreadControl::slotStatut($user, $pm).'</div>
        </div>
        ';
    }
}
