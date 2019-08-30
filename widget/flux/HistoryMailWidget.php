<?php
class HistoryMailWidget extends Widget{
    public function __construct(User $user, Post $post, PostManager $manager){
        parent::__construct(
            '',
            $this->subConstruct($user, $post, $manager),
            '',
            'HistoryMail',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(User $user, Post $post, PostManager $manager){
        global $hash;
        $id = ThreadControl::getId($user);
        $history = new FluxWidget($post, $manager);
        return
        '<p  class="width" style="text-align: center;"><img src="https://onisowo.com/style/icon.png" alt="icon" style="width: 60px; height: 60px;"/></p>
        <h1  class="width" style="text-align:center;">Notify</h1>
        <p class="width">Update has arrived for the flux "'.$post->getData()['title'].'"</p>
        <hr class="space width"/>
        '.$history->getContent().'
        <hr class="space width"/>
        <div  class="width" style="text-align: center">
            <a href="https://onisowo.com/index.php?thread='.$hash->get($post->getId()).'&request=3&user='.$id.'&token='.$post->getData()['keys'][$id].'">unsubscribe</a><br/>
            <p>Developed by otgfeak</p>
            <p style="color:#A5A5A5; font-size: 15px;">
                Be sure to never purchase or enter informations from link in mails that could be ours, hackers are on the rise nowadays.
            </p>
        </div>';
    }
}
