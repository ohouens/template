<?php
class ListFluxWidget extends Widget{
    public function __construct(User $user, PostManager $manager){
        parent::__construct(
            '',
            $this->subConstruct($user, $manager),
            '',
            'ListFlux',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(User $user, PostManager $manager){
        global $hash;
        $page = "";
        $blog = $manager->get($_GET['thread']);
        $list = ThreadControl::list($user, $manager);
        foreach($list as $inter){
            if($inter->getType() != Constant::THREAD_FLUX)
                continue;
            $page .=
            '<p class="list addF" style="margin-top:2vh; margin-bottom:2vh;">
                <a class="link" href="index.php?thread='.$hash->get($_GET['thread']).'&flux='.$hash->get($inter->getId()).'&add">
                    '.$inter->getData()['title'].'<br/>
                    '.substr($inter->getField(),0,200).'
                </a>
            </p>';
        }
        if($page == "")
            return
            '<div class="square" style="height:83vh;">
                <p class="center">
                    You have no flux. <a href="index.php?thread=none&creation" style="color: #33F7B7" class="link">Create one flux to be able to listen this thread.</a>
                </p>
            </div>';
        return
        '<div id="thread">
            <p style="margin-top:2vh; margin-bottom:2vh; font-size:1.5em; font-family:sourceBold;">Choose your flux which will listen to the thread <a target="_blank" href="index.php?thread='.$hash->get($_GET['thread']).'" style="color: #33F7B7" class="link">'.$blog->getData()['title'].'</a></p>
            '.$page.'
        </div>';
    }
}
