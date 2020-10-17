<?php
class ListKatalogiWidget extends Widget{
    public function __construct(User $user, PostManager $manager){
        parent::__construct(
            '',
            $this->subConstruct($user, $manager),
            '',
            'ListKatalogi',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(User $user, PostManager $manager){
        global $hash;
        $page = "";
        foreach(array_reverse($user->getData()['posters']) as $num){
            $inter = $manager->get($num);
            if(is_int($inter)){
                continue;
            }
            $lock = "unlocked";
            if(!$inter->getData()['open'])
                $lock = "locked";
            $page .=
            '<p class="list alignement">
                <a class="link" href="index.php?katalogi=settings&poster='.$hash->get($inter->getId()).'">
                    '.$inter->getData()['title'].'<br/>
                    type:'.Katalogi::getSubType($inter).'<br/>
                    date:'.date("d/m/Y", $inter->getCreation()).'<br/>
                </a><img class="lock" src="style/icon/'.$lock.'.png" alt="'.$lock.'"/>
            </p>';
        }
        if($page == "")
            return '
            <div id="noThread" class="children square">
                <div class="center">
                    <p>
                        <img src="style/icon/void.png" alt="that\'s sad" style="width:250px;"/><br/>
                        You have made no poster<br/>
                    </p>
                </div>
            </div>
            ';
        return
        '<div id="thread">
            '.$page.'
        </div>';
    }
}