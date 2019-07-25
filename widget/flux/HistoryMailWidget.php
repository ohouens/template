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
        $id = $user->getEmail();
        if($user->getPseudo() != "")
            $id = $user->getId();
        $history = new FluxWidget($post, $manager);
        return
        '<p style="text-align: center;"><img src="https://onisowo.com/style/logo.png" alt="icon" style="width: 60px; height: 60px;"/></p>
        <h1 style="text-align:center;">Notify</h1>
        <p>Update has arrived for the flux "'.$post->getData()['title'].'"</p>
        <hr/>
        '.$history->getContent().'
        <hr/>
        <div style="text-align: center">
            <a href="https://onisowo.com/index.php?thread='.$post->getId().'&amp;request=3&amp;user='.$id.'&amp;token='.$post->getData()['keys'][$user->getEmail()].'">unsubscribe</a><br/>
            <p>Developed by ohouens</p>
        </div>';
    }
}
