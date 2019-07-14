<?php
class QrCode{
    public static function code($link, $writing){
        return '
        <div class="presentation">
            <img id="qrCode" src="https://chart.googleapis.com/chart?cht=qr&amp;chs=500x500&amp;chl=http://localhost/ohouens/project/onisowo/'.$link.'" alt="http://localhost/ohouens/project/onisowo/'.$link.'"/>
            <div class="square">
                <div class="center">
                    '.$writing.'
                </div>
            </div>
        </div>';
    }
}
