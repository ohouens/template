<?php
class DisplayThreadWidget extends Widget{
    public static function subscribers(User $user, Post $post, $subscribers){
        global $hash;
        $result = "";
        foreach($subscribers as $inter){
            $id = $inter->getPseudo();
            $link = '?page='.$id;
            $display = $inter->getPseudo();
            if($inter->getData()['isMail']){
                $id = $inter->getEmail();
                $link = "mailto:".$id;
                $display = $id;
            }
            $pp = "";
            if(isset($inter->getData()['pp']))
                $pp = '<img class="profilePicture" src="media/user/pp/'.$inter->getData()['pp'].'" alt="profile picture">';
            $delete = "";
            $special = '<div class="special alignement" thread="'.$hash->get($post->getId()).'" num="'.$id.'"></div>';
            if($user->getId() == $post->getUser()){
                $delete =
                '<a class="removeU" href="?thread='.$hash->get($post->getId()).'&amp;delete='.$id.'">
                    <img class="delete" src="style/icon/wrong.png" alt="delete"/>
                </a>';
            }
            $result .=
            '<p class="name alignement">
                <a href="'.$link.'" class="link">
                    '.$pp.'
                    '.$display.'
                </a>
                '.$delete.'
            </p>'.$special.'
            <hr>';
        }
        return $result;
    }

    public function __construct(User $user, Post $post, UserManager $manager, PostManager $pm){
        parent::__construct(
            '',
            $this->subConstruct($user, $post, $manager, $pm),
            '',
            'displayForum',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(User $user, Post $post, UserManager $manager, PostManager $pm){
        ThreadControl::updateNumber($user, $post, $manager);
        if(!ThreadControl::checkMode($user, $post, "read", $pm))
            return '<div class="rectangle" style="height:81vh;"><p class="center">this thread is private, your not enable to see it</p></div>';
        $result = '<div class="flickery">';
        switch($post->getType()){
            case Constant::THREAD_FORUM:
                $result .= $this->constructForum($user, $post, $manager, $pm);
                break;
            case Constant::THREAD_FLUX:
                $result .= $this->constructFlux($user, $post, $manager);
                break;
            case Constant::THREAD_TICKETING:
                $result .= $this->constructTicketing($user, $post, $manager);
                break;
            case Constant::THREAD_LIST:
                $result .= $this->constructList($user, $post, $manager);
                break;
            default:
                return '';
                break;
        }
        $result .= '</div>';
        return $result;
    }

    private function constructForum(User $user, Post $post, UserManager $manager, PostManager $pm){
        global $hash;
        $option = '';
        // if($user->getId() == $post->getUser() or in_array($user->getPseudo(), $post->getData()['writers']))
            $option = '
            <div id="voteBlock" class="kid plein vide">
                <input type="text" class="input" placeholder="question ?" name="question"><br>
                <input type="text" class="little input" placeholder="answer 1" name="a1">
                <input type="text" class="little input" placeholder="answer 2" name="a2">
                <input type="text" class="little input" placeholder="answer 3" name="a3">
                <input type="text" class="little input" placeholder="answer 4" name="a4">
            </div>
            ';
        $chat = "";
        $grand = ' style="height: 100%;"';
        if(ThreadControl::checkMode($user, $post, "write", $pm)){
            $chat = '
            <form id="sendChat" action="index.php?thread='.$hash->get($post->getId()).'&amp;request=2" method="post">
                <input type="hidden" name="state" value="0">
                <input type="hidden" name="cursor" value="0">
                <div class="slide alignement">
                    '.$option.'
                    <textarea id="areaChat" name="answer" class="kid plein emojiable-question"></textarea>
                </div><!--
                --><input class="cache" type="image" id="delete" src="style/icon/delete.png"/><!--
                --><input class="cache" type="image" id="edit" src="style/icon/edit.png"/><!--
                --><input class="nonCache" type="image" id="addAction" src="style/icon/plus.png"/><!--
                --><input class="nonCache" type="image" id="trigger" src="style/icon/emoji.png"/><!--
                --><input class="nonCache vide" type="image" id="send" src="style/icon/sendDirect.png"/>
            </form>';
            $grand = '';
        }
        return
        '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$hash->get($post->getId()), 'Access forum').'
                '.QrCode::code('index.php?thread='.$hash->get($post->getId()).'%26add', 'Add to flux', 'invisible').'
            </div>
        </div><!--
        --><div id="contentChat" class="grand children alignement" num="'.$hash->get($post->getId()).'">
            <div id="displayChat"'.$grand.'>
                <div id="history" class="vide"></div>
                <div id="end"></div>
            </div>
            <div id="buffer" class="vide"></div>
            '.$chat.'
            <div id="displayLock" class="vide">
                <div class="center">
                    <div id="notifyVote" class="vide">
                        <button id="a1" class="kid buttonA"></button>
                        <button id="a2" class="kid buttonA"></button>
                        <button id="a3" class="kid buttonA"></button>
                        <button id="a4" class="kid buttonA"></button>
                    </div>
                </div>
            </div>
        </div><!--
        --><div id="contentStatistic" class="grand children alignement">
            <div id="contentAuthor" class="square">
                <div class="center">
                    <a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'"><img class="profilePicture" src="media/user/pp/'.ForumControl::getAutor($post->getUser(), $manager)->getData()['pp'].'" alt="profile"></a>
                    <p class="pseudo"><a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'">'.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'</a></p><br/>
                    <p class="gris"><a class="link" href="?thread='.$hash->get($post->getId()).'&amp;settings">Create '.ForumControl::getSeniority($post).'</a></p>
                </div>
            </div>
            <div id="contentFollow" class="square section">
                <p class="center">
                    <span id="followers" class="number">'.count($post->getData()['followers']).'</span><br/>
                    <span class="aa">Followers</span><br/>
                    <button id="follow" class="buttonC space" num="'.$hash->get($post->getId()).'"></button>
                </p>
            </div>
            <div id="contentWriter" num="'.$hash->get($post->getId()).'"></div>
        </div>';
    }

    private function constructFlux(User $user, Post $post, UserManager $manager){
        global $hash;
        $button = '<br/><button id="subscribe" class="buttonC space" num="'.$hash->get($post->getId()).'"></button>';
        if($post->getUser() == $user->getId())
            $button = '<br/><button class="buttonA" id="addFlux">Add flux</button>';
        $notify = "";
        if(FluxControl::isNotify($post))
            $notify = ' <img src="style/icon/notify.png" alt="notify" class="notify"/>';
        $alert = "";
        if(ThreadControl::isAlert($post))
            $alert = ' <img src="style/icon/notification.png" alt="alert on subscribe" class="notify"/>';
        $declare = "";
        $cancel = "";
        if($user->getId() == $post->getUser()){
            $declare = '<img src="style/icon/plusB.png" id="declare" alt="declare" class="vide">';
            $cancel = '<img src="style/icon/plusB.png" id="cancel" alt="declare" class="vide">';
        }
        return
        '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$hash->get($post->getId()).'%26request=3', 'Subscribe').'
                '.QrCode::code('index.php?thread='.$hash->get($post->getId()).'%26add', 'Add to flux', 'invisible').'
            </div>
        </div><!--
        --><div id="contentFlux" class="mainSection grand children rectangle" num="'.$hash->get($post->getId()).'">
            <div id="flux" class="grand large">
                <h1>'.$post->getData()['title'].$alert.$notify.'</h1>
                '.$declare.'
                <div id="fluxLast"></div>
            </div>
            <div class="grand large vide">
                <div class="center">
                    '.$cancel.'
                    <form action="index.php?thread='.$hash->get($post->getId()).'&amp;request=2" method="post">
                        <textarea class="noBorder" name="answer"></textarea>
                        <span>500</span>
                    </form><br/>
                    <button class="buttonA">Add</button><br/>
                    <span id="erreur"></span>
                </div>
            </div>
        </div><!--
        --><div id="contentStatistic" class="grand children alignement">
            <div id="contentAuthor" class="square">
                <div class="center">
                    <a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'"><img class="profilePicture" src="media/user/pp/'.ForumControl::getAutor($post->getUser(), $manager)->getData()['pp'].'" alt="profile"></a>
                    <p class="pseudo"><a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'">'.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'</a></p><br/>
                    <p class="gris"><a class="link" href="?thread='.$hash->get($post->getId()).'&amp;settings">Create '.ForumControl::getSeniority($post).'</a></p>
                </div>
            </div>
            <div id="contentSubscriber" class="square section">
                <p class="center">
                    <span class="number">'.count($post->getData()['subscribers']).'</span><br/>
                    <span class="aa">Subscribers</span>
                    '.$button.'
                </p>
            </div>
            <div id="contentWriter" num="'.$hash->get($post->getId()).'"></div>
        </div>';
    }

    private function constructTicketing(User $user, Post $post, UserManager $manager){
        global $hash;
        $timer = "Closed";
        if(strtotime($post->getField()) > time())
            $timer = $post->getField();
        $alert = "";
        if(ThreadControl::isAlert($post))
            $alert = ' <img src="style/icon/notification.png" alt="alert on subscribe" class="notify"/>';
        $cancel = "";
        if(in_array($user->getId(), $post->getData()[ThreadControl::getInfluence($post)]))
            $cancel = '<p><a class="link" href="index.php?thread='.$hash->get($post->getId()).'&amp;request=3&amp;token='.$post->getData()["keys"][$user->getId()].'">cancel</a></p>';
        return
        '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$hash->get($post->getId()).'%26request=3', 'Enroll').'
                '.QrCode::code('index.php?thread='.$hash->get($post->getId()).'%26add', 'Add to flux', 'invisible').'
            </div>
        </div><!--
        --><div id="displayWriter" class="mainSection grand children alignement" num="'.$hash->get($post->getId()).'">
                <h1>'.$post->getData()['title'].$alert.'</h1>
                <div id="contentWriter" class="ticketing" num="'.$hash->get($post->getId()).'"></div>
        </div><!--
        --><div id="contentStatistic" class="grand children alignement">
            <div id="contentAuthor" class="square">
                <div class="center">
                    <a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'"><img class="profilePicture" src="media/user/pp/'.ForumControl::getAutor($post->getUser(), $manager)->getData()['pp'].'" alt="profile"></a>
                    <p class="pseudo"><a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'">'.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'</a></p><br/>
                    <p class="gris"><a class="link" href="?thread='.$hash->get($post->getId()).'&amp;settings">Create '.ForumControl::getSeniority($post).'</a></p>
                </div>
            </div>
            <div id="contentTicket" class="square section">
                <p class="center">
                    <span class="number">'.count($post->getData()['tickets']).'</span><br/>
                    <span class="aa">Tickets</span>
                </p>
            </div>
            <div id="contentCountdown" class="square">
                <div class="center">
                    <p class="countdown" date="'.$timer.'"></p>
                    '.$cancel.'
                </div>
            </div>
        </div>';
    }

    private function constructList(User $user, Post $post, UserManager $manager){
        global $hash;
        $qrString = "Follow all";
        $token="";
        if(!in_array($user->getId(), $post->getData()["followers"])){
            $token="%26token";
            $qrString = "Unfollow all";
        }
        return
        '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$hash->get($post->getId()).'%26request=3%26redirect%26only', "Follow", 'invisible').'
                '.QrCode::code('index.php?thread='.$hash->get($post->getId()).'%26request=3%26redirect'.$token, $qrString).'
            </div>
        </div><!--
        --><div id="displayList" class="mainSection grand children alignement">
            <div id="threadList" class="grand"  num="'.$hash->get($post->getId()).'"></div>
        </div><!--
        --><div id="contentStatistic" class="grand children alignement">
            <div id="contentAuthor" class="square">
                <div class="center">
                    <a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'"><img class="profilePicture" src="media/user/pp/'.ForumControl::getAutor($post->getUser(), $manager)->getData()['pp'].'" alt="profile"></a>
                    <p class="pseudo"><a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'">'.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'</a></p><br/>
                    <p class="gris"><a class="link" href="?thread='.$hash->get($post->getId()).'&amp;settings">Create '.ForumControl::getSeniority($post).'</a></p>
                </div>
            </div>
            <div id="contentList" class="square section">
                <p class="center">
                    <span class="number">'.count($post->getData()['list']).'</span><br/>
                    <span class="aa">Threads</span><br/>
                    <button id="follow" class="buttonC space" num="'.$hash->get($post->getId()).'"></button>
                </p>
            </div>
            <div id="contentWriter" class="list" num="'.$hash->get($post->getId()).'"></div>
        </div>';
    }
}
