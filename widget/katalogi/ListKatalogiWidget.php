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
            $lock = "<span class='vert'>open</span>";
            if(!$inter->getData()['open'])
                $lock = "<span style='color:red;'>closed</span>";
            $page .=
            '<a href="index.php?katalogi=settings&poster='.$hash->get($inter->getId()).'" style="color:white; text-decoration:none; display:inline-block; width:300px;height:500px; background:url(\'media/forum/cover/'.$inter->getData()['cover'].'\');background-size:cover;">
                <div style="background:rgba(0,0,0,0.5); display:flex; width:100%; height:100%;">
                    <div class="center">
                        '.$inter->getData()['title'].'<br/>
                        type: '.Katalogi::getSubType($inter).'<br/>
                        date: '.date("d/m/Y", $inter->getCreation()).'<br/>
                        statut: '.$lock.'
                    </div>
                </div>
            </a>';
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
