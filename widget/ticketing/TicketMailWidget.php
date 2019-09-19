<?php
class TicketMailWidget extends widget{
    public function __construct(User $customer, Post $post){
        parent::__construct(
            '',
            $this->subConstruct($customer, $post),
            '',
            'TicketMail',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(User $customer, Post $post){
        global $hash;
        $novo = "";
        if($customer->getPseudo() == "")
            $novo =
            '<div class="width">
                Do you want to get other tickets without giving your email each time ?
                <form action="https://onisowo.com/index.php" method="get" style="text-align:center;">
                    <input type="hidden" name="origin" value="mail"/>
                    <input type="submit" value="Sign in" style="display:inline; cursor:pointer; border:none; color:#ffffff; background:#3e3e3e; height: 30px; width: 200px;" />
                </form>
            </div>
            <hr class="space width"/>';
        $id = ThreadControl::getId($customer);
        return
        '<h1 class="width" style="text-align:center;">Ticket</h1>
        <p class="width">
            This is your ticket from "'.$post->getData()['title'].'"<br/>
            you can let the owner scan the qrCode to validate your ticket.
        </p>
        '.QrCode::code(
            '?thread='.$hash->get($post->getId()).'%26request=7%26customer='.$id.'%26token='.$post->getData()['keys'][$id],
            '',
            300
         ).'
        <hr class="space width"/>
        '.$novo.'
        <div  class="width" style="text-align: center">
            <a href="https://onisowo.com/index.php?thread='.$hash->get($post->getId()).'&request=3&user='.$id.'&token='.$post->getData()['keys'][$id].'">cancel</a>
        </div>';
    }
}
