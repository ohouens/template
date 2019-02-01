<?php
interface Subscription
{
    public function subscribe($id);
    public function unsubscribe($id);
}
