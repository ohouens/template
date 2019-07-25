<?php
class SubscribeMailWidget extends Widget{
    public function __construct(User $user, Post $post, PostManager $manager){
        parent::__construct(
            '',
            $this->subConstruct($user, $post, $manager),
            '',
            'subscribeMail',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(User $user, Post $post, PostManager $manager){
        $history = new FluxWidget($post, $manager);
        return
        '<p style="text-align: center;"><img src="https://onisowo.com/style/logo.png" alt="icon" style="width: 60px; height: 60px;"/></p>
        <h1 style="text-align:center;">New Subscription</h1>
        <p>You just subscribed to the flux "'.$post->getData()['title'].'"</p>
        <hr/>
        '.$history->getContent().'
        <hr/>
        <div>
            Do you want to subscribe to other flux without giving your email each time ?
            <form action="https://onisowo.com/" style="text-align:center;">
                <input type="submit" value="Sign in" style="display:inline; cursor:pointer; border:none; color:#ffffff; background:#3e3e3e; height: 30px; width: 200px;" />
            </form>
        </div>
        <hr/>
        <div style="text-align: center">
            <a href="https://onisowo.com/index.php?thread='.$post->getId().'&amp;request=3&amp;user='.$user->getEmail().'&amp;token='.$post->getData()['keys'][$user->getEmail()].'">unsubscribe</a><br/>
            <p>Developed by ohouens</p>
        </div>';
    }
}
