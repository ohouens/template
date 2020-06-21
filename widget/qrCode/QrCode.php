<?php
class QrCode{
    public static function code($link, $writing, $dimension=500){
        return '
        <div class="presentation" style="text-align: center;">
            <img class="qrCode" src="https://chart.googleapis.com/chart?cht=qr&amp;chs='.$dimension.'x'.$dimension.'&amp;chl=http://onisowo.com/'.$link.'" alt="http://onisowo.com/'.$link.'" value="a"/>
            <span class="qrLink" style="display:none;">http://onisowo.com/'.preg_replace("#%26#", "&", $link).'</span>
            <div class="square">
                <div class="center">
                    '.$writing.'
                </div>
            </div>
            <div class="qrInfo" class="space"></div>
        </div>';
    }
}
