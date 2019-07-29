<?php
class LicenceRequiredWidget extends Widget{
    public function __construct(User $user, Widget $content){
        parent::__construct(
            '',
            $this->subConstruct($user, $content),
            '',
            'LicenceRequired',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(User $user, Widget $content){
        if(isset($user->getData()['licence']))
            return $content->getContent();
        return
        '<div id="noLicence" class="children square">
            <div class="center">
                <p>
                    <img src="style/icon/sad.png" alt="that\'s sad"/><br/>
                    A licence is Required to execute this action<br/>
                    <button id="buyLicence" class="button space">Get Licence</button>
                </p>
            </div>
        </div>';
    }
}
