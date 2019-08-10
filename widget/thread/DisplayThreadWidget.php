<?php
class DisplayThreadWidget extends Widget{
    public static function subscribers(User $user, Post $post, $subscribers){
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
                '<a href="?thread='.$post->getId().'&amp;delete='.$id.'">
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
            </p><div class="special alignement" thread="'.$post->getId().'" num="'.$id.'"></div>
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
        switch($post->getType()){
            case Constant::THREAD_FORUM:
                return $this->constructForum($user, $post, $manager);
                break;
            case Constant::THREAD_FLUX:
                return $this->constructFlux($user, $post, $manager);
                break;
            case Constant::THREAD_TICKETING:
                return $this->constructTicketing($user, $post, $manager);
            default:
                return '';
                break;
        }
    }

    private function constructForum(User $user, Post $post, UserManager $manager){
        return
        '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$post->getId(), 'Access forum').'
            </div>
        </div><!--
        --><div id="contentChat" class="grand children alignement" num="'.$post->getId().'">
            <div id="displayChat"></div>
            <form action="index.php?thread='.$post->getId().'&amp;request=2" method="post">
                <textarea name="answer"></textarea><!--
                --><input type="image" id="addAction" src="style/icon/plus.png"/><!--
                --><input type="image" id="send" src="style/icon/sendDirect.png"/>
            </form>
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
                    <button id="follow" class="buttonC space" num="'.$post->getId().'"></button>
                </p>
            </div>
            <div id="contentWriter" num="'.$post->getId().'"></div>
        </div>';
    }

    private function constructFlux(User $user, Post $post, UserManager $manager){
        $button = "";
        if($post->getUser() == $user->getId())
            $button = '<br/><button class="buttonA" id="addFlux">Add flux</button>';
        return
        '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$post->getId().'%26request=3', 'Subscribe').'
            </div>
        </div><!--
        --><div id="contentFlux" class="mainSection grand children rectangle" num="'.$post->getId().'">
            <div id="flux" class="grand large">
                <h1>'.$post->getData()['title'].'</h1>
                <div id="fluxLast"></div>
            </div>
            <div class="grand large vide">
                <div class="center">
                    <form action="index.php?thread='.$post->getId().'&amp;request=2" method="post">
                        <textarea class="noBorder" name="answer"></textarea>
                        <span>300</span>
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
            <div id="contentWriter" num="'.$post->getId().'"></div>
        </div>';
    }

    private function constructTicketing(User $user, Post $post, UserManager $manager){
        return
        '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$post->getId().'%26request=3', 'Get ticket').'
            </div>
        </div><!--
        --><div id="contentCountdown" class="mainSection grand children rectangle" num="'.$post->getId().'">
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
            <div id="contentWriter" class="ticketing" num="'.$post->getId().'"></div>
        </div>';
    }
}
