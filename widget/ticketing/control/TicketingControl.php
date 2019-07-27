<?php
class TicketingControl {
    public static function subscribe(User $user, Post $post, PostManager $manager){
        $state = ThreadControl::subscribe('tickets', $user, $post, $manager);
        if($state == 0){
            //User check creation
            $id = $user->getId();
            if($user->getPseudo() == "")
                $id = $user->getEmail();
            $check = $post->getData()['check'];
            $check[$id] = 0;
            $post->addData(['check' => $check]);
            $manager->update($post);
            //Email sending
            $corps = new TicketMailWidget($user, $post, $manager);
            $mail = new WrapperMail($post->getData()['title'], $user, $corps);
            $mail->send();
        }
        return $state;
    }

    public static function unsubscribe(User $user, Post $post, PostManager $manager){
        $state = ThreadControl::unsubscribe('tickets', $user, $post, $manager);
        if($state == 0){
            //User check deleting
            $id = ThreadControl::getId($user);
            $check = $post->getData()['check'];
            unset($check[$id]);
            $post->addData(['check' => $check]);
            $manager->update($post);
        }
        return $state;
    }

    public static function ticketValidation(User $controller, User $customer, Post $post, PostManager $manager){
        //init users
        if($post->getUser() != $controller->getId())
            return Constant::ERROR_CODE_USER_WRONG;
        $id = ThreadControl::getId($customer);
        //verif code customer
        $pass = $post->getData()['keys'];
        if($pass[$id] != $customer->getData()['pass'])
            return 2;
        //verif existance ticket
        $check = $post->getData()['check'];
        if(!in_array($id, $check))
            return 1;
        //change statut of ticket
        $check[$id] = 1;
        $post->addData(['check' => $check]);
        $manager->update($post);
    }
}
