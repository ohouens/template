<?php
class DisplayForumWidget extends Widget{
    public function __construct(Post $post, UserManager $manager){
        parent::__construct(
            '',
            '<div id="contentCode" class="grand children rectangle">
                <div class="center">
                    <img id="qrCode" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&amp;data=http://localhost/ohouens/project/onisowo/index.php?thread='.$post->getId().'" alt="qrCode"/>
                </div>
            </div><!--
            --><div id="contentChat" class="grand children alignement">
                <div id="displayChat"></div>
                <form action="" method="post">
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
                        <button id="subscribe" class="buttonC space">Follow</button>
                    </p>
                </div>
                <div id="contentWriter" class="square" num="'.$post->getId().'">
                    <div class="center"></div>
                </div>
            </div>',
            '',
            'displayForum',
            '',
            false,
            false
        );
        $this->build();
    }
}
