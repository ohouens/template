<?php
class PageWidget extends Widget{
    private $_user;

    public function __construct(User $user){
        parent::__construct(
            '',
            $this->subConstruct($user),
            '',
            'Page',
            '',
            false,
            false
        );
        $this->build();
        $this->_user = $user;
    }

    private function subConstruct(User $user){
        $licence = "";
        if(isset($user->getData()['licence']))
            $licence = "licence";
        $flag = "";
        if(isset($user->getData()['country']))
            $flag = '<img class="flag alignement" src="https://www.countryflags.io/'.$user->getData()['country'].'/flat/64.png" alt="'.$user->getData()['country'].'"/>';
        $gender = "";
        if(isset($user->getData()['gender'])){
            $tmp = SettingControl::gender($user);
            $gender = '<img src="media/dataProfiling/gender/'.$tmp.'.png" alt="'.$tmp.'"/>';
        }
        $age = "";
        if(isset($user->getData()['birth'])){
            $date = $user->getData()['birth'];
            $now = date("Y-m-d");
            $diff = date_diff(date_create($date), date_create($now));
            $age =
            '<p>
                <span>'.$diff->format('%y').'</span><br>
                years old
            </p>';
        }
        $statut = "";
        if(isset($user->getData()['social']))
            $statut = '<img class="large" src="media/dataProfiling/social/'.$user->getData()['social'].'.png" alt="'.$user->getData()['social'].'" val="'.$user->getData()['social'].'"/>';

        return
        '<div id="pageCover" class="square">
            <div class="center large">
                <img class="profilePicture alignement" src="media/user/pp/'.$user->getData()['pp'].'" alt=" ">
                <h1 class="alignement '.$licence.'">'.$user->getPseudo().'</h1>
                '.$flag.'
            </div>
        </div>
        <div id="pageInfo">
            <div class="rectangle gender">
                <div class="center">
                    '.$gender.'
                </div>
            </div><!--
            --><div class="rectangle birth">
                <div class="center">
                    '.$age.'
                </div>
            </div><!--
            --><div class="rectangle statut">
                <div class="center">
                    '.$statut.'
                </div>
            </div>
        </div>';
    }
}
