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
        $novo = "";
        if($customer->getPseudo() == "")
            $novo =
            '<div>
                Do you want to get other tickets without giving your email each time ?
                <form action="https://onisowo.com/index.php" method="get" style="text-align:center;">
                    <input type="hidden" name="origin" value="mail"/>
                    <input type="submit" value="Sign in" style="display:inline; cursor:pointer; border:none; color:#ffffff; background:#3e3e3e; height: 30px; width: 200px;" />
                </form>
            </div>
            <hr/>';
        $id = ThreadControl::getId($customer);
        return
        '<p style="text-align: center;"><img src="https://onisowo.com/style/logo.png" alt="icon" style="width: 60px; height: 60px;"/></p>
        <h1 style="text-align:center;">Ticket</h1>
        <p>
            This is your ticket from "'.$post->getData()['title'].'"<br/>
            you can let the owner scan the qrCode to validate your ticket.
        </p>
        '.QrCode::code(
            '?thread='.$post->getId().'&amp;request=7&amp;customer='.$id.'&amp;token='.$post->getData()['keys'][$id],
            '',
            300
         ).'
        <hr/>
        '.$novo.'
        <div style="text-align: center">
            <a href="https://onisowo.com/index.php?thread='.$post->getId().'&amp;request=3&amp;user='.$id.'&amp;token='.$post->getData()['keys'][$id].'">cancel</a><br/>
            <p>Developed by ohouens</p>
        </div>';
    }
}