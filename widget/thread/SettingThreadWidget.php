<?php
class SettingThreadWidget extends Widget{
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
        $lock = "no";
        if($post->getData()['writers'] == [])
            $lock = "yes";
        $save = "";
        if($user->getId() == $post->getUser()){
            $yes = "";
            $no = "";
            if($post->getData()['writers'] == [])
                $yes = "selected";
            else $no = "selected";
            $lock =
            '<form class="alignement" method="post" action="index.php?thread='.$hash->get($post->getId()).'&amp;request=8">
                <select class="input" name="open">
                    <option value="yes" '.$yes.'>yes</option>
                    <option value="no" '.$no.'>no</option>
                </select>
            </form>';
            $save =
            '<button id="delete" class="button space">Delete</button>
            <button id="save" class="button space">Save</button>';
        }
        return
        '<div id="setWidget" class="children square">
            <div class="large center">
                <div id="resume" num="'.$hash->get($post->getId()).'">
                    <h1>'.$post->getData()['title'].'</h1>
                    <div class="list">
                        <span class="tab">type:</span>'.ThreadControl::getType($post).'<br/>
                        <span class="tab">date:</span>'.date("d/m/Y", $post->getCreation()).'<br/>
                        <span class="tab">influence:</span>'.count($post->getData()[ThreadControl::getInfluence($post)]).'<br/>
                        <span class="tab">open:</span>'.$lock.'
                    </div>
                    '.$save.'
                    <button id="view" class="button space">View</button><br/>
                    <span id="error"></span>
                </div>
            </div>
        </div>';
    }
}
