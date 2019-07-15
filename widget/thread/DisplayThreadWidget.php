<?php
class DisplayThreadWidget extends Widget{
    public function __construct(Post $post, UserManager $manager){
        parent::__construct(
            '',
            $this->subConstruct($post, $manager),
            '',
            'displayForum',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(Post $post, UserManager $manager){
        switch($post->getType()){
            case Constant::THREAD_FORUM:
                return $this->constructForum($post, $manager);
                break;
            case Constant::THREAD_FLUX:
                return $this->constructFlux($post, $manager);
                break;
            case Constant::THREAD_TICKETING:
                return $this->constructTicketing($post, $manager);
            default:
                return '';
                break;
        }
    }

    private function constructForum(Post $post, UserManager $manager){
        return '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$post->getId(), 'Scan to access').'
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
                    <div class="center profilePicture" style="background-image: url(media/user/pp/'.ForumControl::getAutor($post->getUser(), $manager)->getData()['pp'].');"></div>
                    <p class="pseudo">'.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'</p><br/>
                    <p class="gris">Create '.ForumControl::getSeniority($post).'</p>
                </div>
            </div>
            <div id="contentFollow" class="square">
                <p class="center">
                    <span id="followers">'.count($post->getData()['followers']).'</span><br/>
                    <span id="ff">Followers</span><br/>
                    <button id="follow" class="buttonC space" num="'.$post->getId().'"></button>
                </p>
            </div>
            <div id="contentWriter" class="square" num="'.$post->getId().'">
                <div class="center"></div>
            </div>
        </div>';
    }

    private function constructFlux(Post $post, UserManager $manager){
        return '';
    }

    private function constructTicketing(Post $post, UserManager $manager){
        return '<div id="contentCode" class="grand children rectangle">
            <div class="center">
                '.QrCode::code('index.php?thread='.$post->getId().'%26request=3', 'Scan to access').'
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
                    <div class="center profilePicture" style="background-image: url(media/user/pp/'.ForumControl::getAutor($post->getUser(), $manager)->getData()['pp'].');"></div>
                    <p class="pseudo">'.ForumControl::getAutor($post->getUser(), $manager)->getPseudo().'</p><br/>
                    <p class="gris">Create '.ForumControl::getSeniority($post).'</p>
                </div>
            </div>
            <div id="contentTicket" class="square section">
                <p class="center">
                    <span id="tickets">'.count($post->getData()['tickets']).'</span><br/>
                    <span id="tt">Tickets</span>
                </p>
            </div>
            <div id="contentWriter" class="square" num="'.$post->getId().'">
                <div class="center"></div>
            </div>';
    }
}
