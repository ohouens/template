<?php
class ListThreadWidget extends Widget{
    public function __construct(User $user, PostManager $manager){
        parent::__construct(
            '',
            $this->subConstruct($user, $manager),
            '',
            'ListThread',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(User $user, PostManager $manager){
        global $hash;
        $page = "";
        $list = ThreadControl::list($user, $manager);
        foreach($list as $inter){
            $lock = "unlocked";
            if(!$inter->getData()['open'])
                $lock = "locked";
            $page .=
            '<p class="list alignement">
                <a class="link" href="index.php?thread='.$hash->get($inter->getId()).'&setting">
                    '.$inter->getData()['title'].'<br/>
                    type:'.ThreadControl::getType($inter).'<br/>
                    date:'.date("d/m/Y", $inter->getCreation()).'<br/>
                    influence:'.count($inter->getData()[ThreadControl::getInfluence($inter)]).'<br/>
                </a><img class="lock" src="style/icon/'.$lock.'.png" alt="'.$lock.'"/>
            </p>';
        }
        if($page == "")
            return "";
        return
        '<div id="thread">
            '.$page.'
        </div>';
    }
}
