<?php
class SettingKatalogiWidget extends Widget{
    public function __construct(User $user, Post $post, PostManager $pm){
        parent::__construct(
            '',
            $this->subConstruct($user, $post, $pm),
            '',
            'SettingWidget',
            '',
            false,
            false
        );
        $this->build();
    }

    public function subConstruct(User $user, Post $post, PostManager $pm){
        global $hash;
        if($user->getId() == $post->getUser()){
            $yes = "";
            $no = "";
            $renewYes = "";
            $renewNo = "";
            if($post->getData()['open'])
                $yes = "selected";
            else $no = "selected";
            if($post->getData()['renew'])
                $renewYes = "selected";
            else $renewNo = "selected";
            $renewable = "";
            if(!isset($user->getData()["licence"])){
                $renewable = '
                <span class="tab">renew:</span><select class="input" name="renew">
                    <option value="yes" '.$renewYes.'>yes</option>
                    <option value="no" '.$renewNo.'>no</option>
                </select>';
            }
            $button = "read";
            if($post->getField() == 1)$button = "GPS";
            if($post->getField() == 2)$button = "Code";
            if($post->getField() == 3)$button = "link";
            $rc = "infinity";
            if(!isset($user->getData()['licence']))
                $rc = date("Y-m-d", $post->getData()['lastRenew']+60*60*24*28);
            return '
                <div id="settingPoster" class="child">
                    <div class="grand alignement child" id="preview" style="background-image: url(\'media/forum/cover/'.$post->getData()['cover'].'\');">
                        <div class="super">
                            <div class="center">
                                <h1>'.$post->getData()['title'].'</h1>
                                <p class="desc">'.$post->getData()['desc'].'</p>
                                <button class="buttonC">'.$button.'</button>
                            </div>
                        </div>
                    </div><!--
                    --><div class="grand rectangle child" id="setting" num="'.$hash->get($post->getId()).'">
                        <div class="center" id="resume" num="'.$hash->get($post->getId()).'">
                            <h1>'.$post->getData()['title'].'</h1>
                            <span class="dup link vert">Duplicate+</span> <span id="statutContainer">'.ThreadControl::slotStatut($user, $pm).'</span><br>
                            <form method="post" action="index.php?thread='.$hash->get($post->getId()).'&amp;request=8">
                                '.$post->getData()['address'].'<br>
                                <span class="vert">priority\'s renewal countdown:</span> <span class="renewalCountdown" date="'.$rc.'"></span><br>
                                <div id="addDup"></div>
                                <div id="addresses" class="vide">
                                    <span class="tab">clone:</span><input type="text" id="adAdding" name="adAdding" class="input" placeholder="Address"/><br>
                                    <input type="text" name="extraAddress" placeholder="extra address" class="input vide"/>
                                </div>
                                <span class="tab">open:</span><select class="input" name="open">
                                    <option value="yes" '.$yes.'>yes</option>
                                    <option value="no" '.$no.'>no</option>
                                </select><br>'.$renewable.'
                            </form>
                            <button id="delete" class="button space">Delete</button>
                            <button id="savePoster" class="button space">Save</button><br>
                            <span id="error"></span>
                        </div>
                    </div>
                </div>
            ';
        }
    }
}
