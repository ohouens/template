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
        $selectF = "";
        $selectG = "";
        $birth = "";
        if(isset($user->getData()['gender']) and $user->getData()['gender'] == 0)
            $selectG = "selected";
        if(isset($user->getData()['gender']) and $user->getData()['gender'] == 1)
            $selectB = "selected";
        if(isset($user->getData()['birth']))
            $birth = $user->getData()['birth'];
        return
        '<p>
            <a class="link" href="#changeGender">change gender</a><br/>
            <a class="link" href="#changeCountry">change country</a><br/>
            <a class="link" href="#changeBirth">change birth</a><br/>
            <a class="link" href="#changeSocial">change social</a><br/>
            <a class="link" href="#changeHobbies">change hobbies</a><br/>
        </p>
        <div class="grand vide">
            <form class="vide" id="changeGender" method="post" action="index.php?setting&amp;request=4">
                <input type="hidden" name="gender"/>
                <img class="gender '.$selectG.'" src="media/dataProfiling/gender/girl.png" origin="girl" alt="female" val="0"/>
                <img class="gender '.$selectB.'" src="media/dataProfiling/gender/boy.png" origin="boy" alt="male" val="1"/><br/>
                <input class="buttonC space" type="submit" value="change"/>
            </form>
            <form class="vide" id="changeCountry" method="post" action="index.php?setting&amp;request=5">
                '.SettingUtil::countriesList('input').'
                <img src="https://www.countryflags.io/'.SettingControl::country($user).'/flat/64.png" alt="'.SettingControl::country($user).'"/><br/>
                <input class="buttonC space" type="submit" value="change"/>
            </form>
            <form class="vide" id="changeBirth" method="post" action="index.php?setting&amp;request=6">
                <input type="date" name="birth" class="input" value="'.$birth.'"/><br/>
                <input class="buttonC space" type="submit" value="change"/>
            </form>
            <form class="vide" id="changeSocial" method="post" action="index.php?setting&amp;request=7">
                <input class="buttonC space" type="submit" value="change"/>
            </form>
            <form class="vide" id="changeHobbies" method="post" action="index.php?setting&amp;request=8">
                <input class="buttonC space" type="submit" value="change"/>
            </form>
            <span class="displayError"></span><br/>
            <a class="link" href="#">retour</a>
        </div>';
    }

    private function generalForms(User $user){
        return
        '<p>
            <a class="link" href="#changePdp">change profile picture</a><br/>
            <a class="link" href="#changePassword">change password</a><br/>
            <a class="link" href="#changeEmail">change email</a><br/>
        </p>
        <div class="grand vide">
            <form class="vide" id="changePdp" enctype="multipart/form-data" method="post" action="index.php?setting&amp;request=1">
                <input type="file" name="pdp" accept="image/x-png,image/jpeg" class="vide"/>
                <img class="center profilePicture" src="media/user/pp/'.$user->getData()['pp'].'"/><br/>
                <input class="buttonC space" type="submit" value="change"/>
            </form>
            <form class="vide" id="changePassword" method="post" action="index.php?setting&amp;request=2">
                <input class="input" type="password" name="old" placeholder="old password" /><br/>
                <input class="input" type="password" name="new" placeholder="new password" /><br/>
                <input class="input" type="password" name="confirm" placeholder="confirm new password" /><br/>
                <input class="buttonC space" type="submit" value="change"/>
            </form>
            <form class="vide" id="changeEmail" method="post" action="index.php?setting&amp;request=3">
                <span>current email: '.$user->getEmail().'</span><br/>
                <input class="input" type="text" name="newMail" placeholder="new email"/><br/>
                <input class="buttonC space" type="submit" value="change"/>
            </form>
            <span class="displayError"></span><br/>
            <a class="link" href="#">retour</a>
        </div>';
    }
}
