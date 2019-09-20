<?php
class FooterBannerWidget extends Widget{
    public function __construct(){
        parent::__construct(
            '',
            $this->subConstruct(),
            '',
            'FooterBanner',
            '',
            false,
            false
        );
        $this->build();
    }

    private function subConstruct(){
        return
        '<div id="contact" class="large">
            <div>
                <img id="footerLogo" src="style/logo(1).png" alt="logo onisowo">
                <strong>ONISOWO</strong>
                <a href="mailto:ryan@ohouens.com"><img src="style/icon/mail.png" alt="mail"></a>
            </div>
        </div>';
    }

    //<a href="https://twitter.com/otgfeak/" target="_blank"><img src="style/icon/twitterWhite.png" alt="twitter"></a>
}
