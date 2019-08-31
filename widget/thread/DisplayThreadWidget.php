<?php
class DisplayThreadWidget extends Widget{
    public static function subscribers(User $user, Post $post, $subscribers){
        global $hash;
        $result = "";
        foreach($subscribers as $inter){
            $id = $inter->getId();
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
            if($user->getId() == $post->getUser()){
                $delete =
                '<a href="?thread='.$hash->get($post->getId()).'&amp;delete='.$id.'">
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
            </p><div class="special alignement" thread="'.$hash->get($post->getId()).'" num="'.$id.'"></div>
            <hr>';
        }
        return $result;
    }

    public function __construct(User $user, Post $post, UserManager $manager){
        parent::__construct(
            '',
            $this->subConstruct($user, $post, $manager),
            '',
            'displayForum',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(User $user, Post $post, UserManager $manager){
        $result = '<div class="flickery">';
        switch($post->getType()){
            case Constant::THREAD_FORUM:
                $result .= $this->constructForum($user, $post, $manager);
                break;
            case Constant::THREAD_FLUX:
                $result .= $this->constructFlux($user, $post, $manager);
                break;
            case Constant::THREAD_TICKETING:
                $result .= $this->constructTicketing($user, $post, $manager);
                break;
            default:
                return '';
                break;
        }
        $result .= '</div>';
        return $result;
    }

    private function constructForum(User $user, Post $post, UserManager $manager){
        global $hash;
        return
        '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$hash->get($post->getId()), 'Access forum').'
            </div>
        </div><!--
        --><div id="contentChat" class="grand children alignement" num="'.$hash->get($post->getId()).'">
            <div id="displayChat">
                <div id="history" class="vide"></div>
                <div id="end"></div>
            </div>
            <div id="buffer" class="vide"></div>
            <form id="sendChat" action="index.php?thread='.$hash->get($post->getId()).'&amp;request=2" method="post">
                <input type="hidden" name="state" value="0">
                <div class="slide alignement">
                    <div id="barrierBlock" class="kid plein vide">
                        <div class="center">
                            <input type="hidden" name="length" value="1000">
                            <span>1000</span> secondes
                        </div>
                    </div>
                    <div id="voteBlock" class="kid plein vide">
                        <input type="text" class="input" placeholder="question ?" name="question"><br>
                        <input type="text" class="little input" placeholder="answer 1" name="a1">
                        <input type="text" class="little input" placeholder="answer 2" name="a2">
                        <input type="text" class="little input" placeholder="answer 3" name="a3">
                        <input type="text" class="little input" placeholder="answer 4" name="a4">
                    </div>
                    <textarea name="answer" class="kid plein"></textarea>
                </div><!--
                --><input type="image" id="addAction" src="style/icon/plus.png"/><!--
                --><input type="image" id="send" src="style/icon/sendDirect.png"/>
            </form>
            <div id="displayLock" class="vide">
                <div class="center">
                    <button id="notifyBarrier" class="button vide">Notify</button>
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
                    <a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getId().'"><img class="profilePicture" src="media/user/pp/'.ForumControl::getAutor($post->getUser(), $manager)->getData()['pp'].'" alt="profile"></a>
                    <p class="pseudo"><a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getId().'">'.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'</a></p><br/>
                    <p class="gris">Create '.ForumControl::getSeniority($post).'</p>
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
        $button = "";
        if($post->getUser() == $user->getId())
            $button = '<br/><button class="buttonA" id="addFlux">Add flux</button>';
        return
        '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$hash->get($post->getId()).'%26request=3', 'Subscribe').'
            </div>
        </div><!--
        --><div id="contentFlux" class="mainSection grand children rectangle" num="'.$hash->get($post->getId()).'">
            <div id="flux" class="grand large">
                <h1>'.$post->getData()['title'].'</h1>
                <div id="fluxLast"></div>
            </div>
            <div class="grand large vide">
                <div class="center">
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
                    <img class="profilePicture" src="media/user/pp/'.ForumControl::getAutor($post->getUser(), $manager)->getData()['pp'].'" alt=" ">
                    <p class="pseudo"><a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getId().'">'.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'</a></p><br/>
                    <p class="gris">Create '.ForumControl::getSeniority($post).'</p>
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
        return
        '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$hash->get($post->getId()).'%26request=3', 'Get ticket').'
            </div>
        </div><!--
        --><div id="contentCountdown" class="mainSection grand children rectangle" num="'.$hash->get($post->getId()).'">
            <div class="center">
                <p class="countdown" date="'.$post->getField().'">
                </p>
            </div>
        </div><!--
        --><div id="contentStatistic" class="grand children alignement">
            <div id="contentAuthor" class="square">
                <div class="center">
                    <img class="profilePicture" src="media/user/pp/'.ForumControl::getAutor($post->getUser(), $manager)->getData()['pp'].'" alt=" ">
                    <p class="pseudo"><a class="link" href="?page='.ForumControl::getAutor($post->getUser(), $manager)->getId().'">'.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'</a></p><br/>
                    <p class="gris">Create '.ForumControl::getSeniority($post).'</p>
                </div>
            </div>
            <div id="contentTicket" class="square section">
                <p class="center">
                    <span class="number">'.count($post->getData()['tickets']).'</span><br/>
                    <span class="aa">Tickets</span>
                </p>
            </div>
            <div id="contentWriter" class="ticketing" num="'.$hash->get($post->getId()).'"></div>
        </div>';
    }
}
