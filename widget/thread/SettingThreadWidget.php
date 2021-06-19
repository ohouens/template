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
        $lock = '<span class="tab">open:</span>no';
        if($post->getData()['open'])
            $lock = '<span class="tab">open:</span>yes';
        $save = "";
        if($user->getId() == $post->getUser()){
            $yes = "";
            $no = "";
            if($post->getData()['open'])
                $yes = "selected";
            else $no = "selected";
            $readme = "";
            $readLock = "";
            $writeme = "";
            $executeme = "";
            $tunnel = "";
            $in = "";
            $verrou = "";
            if(isset($post->getData()['read']) and $post->getData()['read'] == 0)
                $readme = "selected";
            if(isset($post->getData()['read']) and $post->getData()['read'] == 2)
                $readLock = "selected";
            if(isset($post->getData()['write']) and $post->getData()['write'] == 0)
                $writeme = "selected";
            if(isset($post->getData()['execute']) and $post->getData()['execute'] == 0)
                $executeme = "selected";
            if(isset($post->getData()['output']))
                foreach($post->getData()['output'] as $t)
                    $tunnel .= $hash->get($t)." ";
            if(isset($post->getData()['input']))
                foreach($post->getData()['input'] as $i)
                    $in .= $hash->get($i)." ";
            if(isset($post->getData()['lock']))
                foreach($post->getData()['lock'] as $l)
                    $verrou .= $hash->get($l)." ";
            $echoTunnel = "";
            if($post->getType() == Constant::THREAD_FLUX)
                $echoTunnel = '<br/>
                <span class="tab">input:</span><textarea class="input" name="input">'.$in.'</textarea>';
                // <span class="tab">output:</span><textarea class="input" name="output">'.$tunnel.'</textarea>';
            $notify = "";
            if($user->getId() == 1 and $post->getType() == Constant::THREAD_FLUX){
                $ny="";
                $nn="selected";
                if(isset($post->getData()['notify']) and $post->getData()['notify']){
                    $nn="";
                    $ny="selected";
                }
                $notify = '
                <span class="tab">notify:</span><select class="input" name="notify">
                    <option value="yes" '.$ny.'>yes</option>
                    <option value="no" '.$nn.'>no</option>
                </select><br/>';
            }
            $alert = "";
            if($user->getId() == 1 and in_array($post->getType(), [Constant::THREAD_FLUX, Constant::THREAD_TICKETING])){
                $ay="";
                $an="selected";
                if(isset($post->getData()['alert']) and $post->getData()['alert']){
                    $an="";
                    $ay="selected";
                }
                $alert = '
                <span class="tab">alert:</span><select class="input" name="alert">
                    <option value="yes" '.$ay.'>yes</option>
                    <option value="no" '.$an.'>no</option>
                </select><br/>';
            }
            $forum="";
            if($post->getType() == Constant::THREAD_FORUM){
                $forum='<br/>
                <span class="tab">write:</span><select class="input" name="write">
                    <option value="everyone">everyone</option>
                    <option value="me" '.$writeme.'>I</option>
                </select><br/>
                <span class="tab">execute:</span><select class="input" name="execute">
                    <option value="everyone">everyone</option>
                    <option value="me" '.$executeme.'>I</option>
                </select>';
            }
            $lock =
            '<form class="alignement" method="post" action="index.php?thread='.$hash->get($post->getId()).'&amp;request=8">
                <span class="tab">open:</span><select class="input" name="open">
                    <option value="yes" '.$yes.'>yes</option>
                    <option value="no" '.$no.'>no</option>
                </select><br/>
                <span class="tab">read:</span><select class="input" name="read">
                    <option value="everyone">everyone</option>
                    <option value="me" '.$readme.'>I</option>
                    <option value="lock1" '.$readLock.'>lock</option>
                </select>
                <span class="tab">lock:</span><textarea class="input" name="lock">'.$verrou.'</textarea>'.$forum.$echoTunnel.$alert.$notify.'
            </form>';
            $save =
            '<button id="deleteThread" class="button space">Delete</button>
            <button id="saveThread" class="button space">Save</button>';
        }
        $listeners = 0;
        if(isset($post->getData()["tunnelv2"]))
            $listeners = count($post->getData()["tunnelv2"]);
        return
        '<div id="setWidget" class="square">
            <div class="large center">
                <div id="resume" num="'.$hash->get($post->getId()).'">
                    <h1>'.$post->getData()['title'].'</h1>
                    <div class="list">
                        <span class="tab">type:</span>'.ThreadControl::getType($post).'<br/>
                        <span class="tab">date:</span>'.date("d/m/Y", $post->getCreation()).'<br/>
                        <span class="tab">influence:</span>'.count($post->getData()[ThreadControl::getInfluence($post)]).'<br/>
                        <span class="tab">listeners:</span>'.$listeners.'<br>
                        '.$lock.'
                    </div>
                    '.$save.'
                    <button id="viewThread" class="button space">View</button><br/>
                    <span id="error"></span>
                </div>
            </div>
        </div>';
    }
}
