<?php
class QrCode{
    public static function code($link, $writing, $visible="visible", $dimension=500){
        return '
        <div class="presentation '.$visible.'" style="text-align: center;">
            <img class="qrCode" src="https://chart.googleapis.com/chart?cht=qr&amp;chs='.$dimension.'x'.$dimension.'&amp;chl=http://onisowo.com/'.$link.'" alt="http://onisowo.com/'.$link.'" value="a"/>
            <span class="qrLink" style="display:none;">http://onisowo.com/'.preg_replace("#%26#", "&", $link).'</span>
            <div class="square">
                <div class="center">
                    <a href="http://onisowo.com/'.preg_replace("#%26#", "&", $link).'" class="link">'.$writing.'</a>
                </div>
            </div>
            <div class="qrInfo" class="space"></div>
        </div>';
    }
}
