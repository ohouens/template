<?php
class NoThreadWidget extends Widget{
    public function __construct(User $user, Widget $content){
        parent::__construct(
            '',
            $this->subConstruct($user, $content),
            '',
            'NoThread',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(User $user, Widget $content){
        if($content->getContent() == "")
            return
            '<div id="noThread" class="children square">
                <div class="center">
                    <p>
                        <img src="style/icon/void.png" alt="that\'s sad" style="width:350px;"/><br/>
                        You have subscribe to 0 thread<br/>
                        You can check associations and commerces near you to subscribe to their content
                    </p>
                </div>
            </div>';
        return $content->getContent();
    }
}
