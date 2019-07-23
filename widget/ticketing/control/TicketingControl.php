<?php
class TicketingControl {
    public static function subscribe(User $user, Post $post, PostManager $manager){
        $state = ThreadControl::subscribe('tickets', $user, $post, $manager);
        if($state == 0){

        }
        return $state;
    }
}
