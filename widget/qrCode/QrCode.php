<?php
class QrCode{
    public static function code($link, $writing){
        return '
        <div class="presentation">
            <img id="qrCode" src="'.$link.'" alt="qrCode"/>
            <div class="square">
                <div class="center">
                    '.$writing.'
                </div>
            </div>
        </div>';
    }
}
