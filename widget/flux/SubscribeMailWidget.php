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
        global $hash;
        $history = new FluxWidget($post, $manager);
        $id = ThreadControl::getId($user);
        $novo = "";
        if($user->getPseudo() == "")
            $novo =
            '<div class="width">
                Do you want to subscribe to other flux without giving your email each time ?
                <form action="https://onisowo.com/index.php" method="get" style="text-align:center;">
                    <input type="hidden" name="origin" value="mail"/>
                    <input type="submit" value="Sign up" style="display:inline; cursor:pointer; border:none; color:#ffffff; background:#3e3e3e; height: 30px; width: 200px;" />
                </form>
            </div>
            <hr class="space width"/>';
        return
        '<h1  class="width" style="text-align:center;">New Subscription</h1>
        <p class="width">You just subscribed to the flux "'.$post->getData()['title'].'"</p>
        <hr class="space width"/>
        '.$history->getContent().'
        <hr class="space width"/>
        '.$novo.'
        <div  class="width" style="text-align: center">
            <a href="https://onisowo.com/index.php?thread='.$hash->get($post->getId()).'&request=3&user='.$id.'&token='.$post->getData()['keys'][$id].'">unsubscribe</a><br/>
        </div>';
    }
}
