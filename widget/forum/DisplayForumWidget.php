<?php
class DisplayForumWidget extends Widget{
    public function __construct(){
        parent::__construct(
            '',
            '<div id="contentCode" class="grand rectangle">
                <div class="center"></div>
            </div>
            <div id="contentChat" class="grand alignement">
                <div id="displayChat"></div>
                <form action="" method="post">
                    <textarea name="answer"></textarea>
                    <input type="image" id="addAction" src="style/icon/plus.png"/>
                    <input type="image" id="send" src="style/icon/send.png"/>
                </form>
            </div>
            <div id="contentStatistic" class="grand alignement">
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
            false,
            false
        );
        $this->build();
    }
}
