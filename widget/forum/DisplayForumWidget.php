<?php
class DisplayForumWidget extends Widget{
    public function __construct(Post $post){
        parent::__construct(
            '',
            '<div id="contentCode" class="grand children rectangle" num="'.$post->getId().'">
                <div class="center">
                    <img id="qrCode" src="" alt="qrCode"/>
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
                        <div class="profilePicture"></div>
                        <div class="trophies"></div>
                        <p class="pseudo"></p>
                        <p class="gris">Create on <span></span></p>
                    </div>
                </div>
                <div id="contentFollow" class="square">
                    <p class="center">
                        <span id="followers"></span><br/>
                        Followers<br/><br/>
                        <button id="subscribe" class="buttonA">Subscribe</button>
                    </p>
                </div>
                <div id="contentWriter" class="square">
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
