<?php
class QrCode{
    public static function code($link, $writing){
        return '
        <div class="presentation">
            <img id="qrCode" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&amp;data=http://localhost/ohouens/project/onisowo/'.$link.'" alt="qrCode"/>
            <div class="square">
                <div class="center">
                    '.$writing.'
                </div>
            </div>
        </div>';
    }
}
