<?php
class SettingKatalogiWidget extends Widget{
    public function __construct(User $user, Post $post){
        parent::__construct(
            '',
            $this->subConstruct($user, $post),
            '',
            'SettingWidget',
            '',
            false,
            false
        );
        $this->build();
    }

    public function subConstruct(User $user, Post $post){
        global $hash;
        if($user->getId() == $post->getUser()){
            $yes = "";
            $no = "";
            if($post->getData()['open'])
                $yes = "selected";
            else $no = "selected";
            $button = "read";
            if($post->getField() == 1)$button = "GPS";
            if($post->getField() == 2)$button = "Code";
            if($post->getField() == 3)$button = "link";
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
                            <form method="post" action="index.php?thread='.$hash->get($post->getId()).'&amp;request=8">
                                <span class="tab">open:</span><select class="input" name="open">
                                    <option value="yes" '.$yes.'>yes</option>
                                    <option value="no" '.$no.'>no</option>
                                </select>
                            </form>
                            <button id="delete" class="button space">Delete</button>
                            <button id="save" class="button space">Save</button><br>
                            <span id="error"></span>
                        </div>
                    </div>
                </div>
            ';
        }
    }
}
