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
                <a href="mailto:ryan@ohouens.com"><img src="style/icon/mail.png" alt="mailto"></a>
                <a href="https://ohouens.com/"><img src="https://ohouens.com/style/icon/ohouens_blanc.png" alt="ohouens"></a>
                <a href="https://twitter.com/ryan_ohouens/"><img src="style/icon/twitter.png" alt="twitter"></a>
            </div>
        </div>';
    }
}