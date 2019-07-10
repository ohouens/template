<?php
class SettingWidget extends Widget{
    private $_user;

    public function __construct(User $user){
        parent::__construct(
            '',
            $this->subConstruct($user),
            '',
            'setting',
            '',
            false,
            false
        );
        $this->build();
        $this->_user = $user;
    }

    private function subConstruct(User $user){
        $result =
        '<div id="settingData" class="alignement children">
            <div class="a super square">
                <div class="center">
                    <h2>Profile data</h2>
                    <p>
                        You can improve your profile by adding data that you want.<br/>
                        This will gives you access to threads which are only accessible for certain type of profile
                        and we will recommend you threads that fits better your preferences.
                    </p>
                    <button class="button">Manage</button>
                </div>
            </div>
            <div class="b grand vide">
                <div class="center large">
                '.$this->dataForms($user).'
                </div>
            </div>
        </div><!--
        --><div id="settingGeneral" class="alignement children">
            <div class="a super square">
                <div class="center">
                    <h2>General setting</h2>
                    <p>
                        You can change information links to your account to improves your experience.
                    </p>
                    <button class="button">Change</button>
                </div>
            </div>
            <div class="b grand vide">
                <div class="center large">
                '.$this->generalForms($user).'
                </div>
            </div>
        </div>';
        return $result;
    }

    private function dataForms(User $user){
        return
        '';
    }

    private function generalForms(User $user){
        return
        '<p>
            <a class="link" href="#changePdp">change profile picture</a><br/>
            <a class="link" href="#changePassword">change password</a><br/>
            <a class="link" href="#changeEmail">change email</a><br/>
        </p>
        <div class="grand vide">
            <form class="vide" id="changePdp" enctype="multipart/dataform" method="post" action="">
                <input type="file" name="pdp" accept="image/x-png,image/jpeg" class="vide"/>
                <div class="center profilePicture" style="background-image: url(media/user/pp/'.$user->getData()['pp'].');"></div>
                <input class="buttonC space" type="submit" value="change"/>
            </form>
            <form class="vide" id="changePassword" method="post" action="">
                <input class="input" type="text" name="old" placeholder="old password" /><br/>
                <input class="input" type="text" name="new" placeholder="new password" /><br/>
                <input class="input" type="text" name="confirm" placeholder="confirm new password" /><br/>
                <input class="buttonC space" type="submit" value="change"/>
            </form>
            <form class="vide" id="changeEmail" method="post" action="">
                <span>current email: '.$user->getEmail().'</span><br/>
                <input class="input" type="text" name="newMail" placeholder="new email"/><br/>
                <input class="buttonC space" type="submit" value="change"/>
            </form>
            <span id="erreurSetting"></span><br/>
            <a class="link" href="#">retour</a>
        </div>';
    }
}
