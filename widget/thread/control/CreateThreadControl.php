<?php
class createThreadControl{
    public static function getObject($thread){
        $retour = "";
        switch ($thread) {
            case 'flux':
                $retour .=
                '<input class="input large" type="text" name="title" placeholder="Title"/><br/>
                <textarea name="intro"></textarea>';
                break;
            case 'forum':
                $retour .=
                '<img class="large" src="style/icon/upload.png" alt="preview"/><br/>
                <input class="input  noBorder" type="text" name="title" placeholder="Title"/>';
                break;
            case 'ticketing':
                $retour .=
                '<input class="input large" type="text" name="title" placeholder="Title"/>
                <input type="date" name="when" class="input"/>';
                break;
            default:
                $retour .= '';
                break;
        }
        return $retour;
    }
}
