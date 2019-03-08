<?php
class PostWidget extends Widget{
    private $_post;

    public function __construct(Post $post){
        parent::__construct(
            "",
            $this->subConstruct($post),
            "",
            "",
            "",
            false,
            false
        );
        $this->_post = $post;
        $this->build();
    }

    private function subConstruct(Post $post){
        $result = "";
        switch($post->getFormat()){
            case Constant::FORMAT_VOTE:
                $result .=
                '<div class="post vote" num="'.$post->getID().'">
                    <div class="field">
                        <div class="titre">
                            '.$post->getField().'
                        </div>
                        <div class="intitule">
                        '.$this->choicesNames($post->getData()['choices']).'
                        </div>
                    </div>
                    <div class="choices">
                        '.$this->choices($post->getData()['choices'], $this->count($post->getData()['v1'], $post->getData()['choices'], $post->getData()['count'])).'
                    </div>
                </div>';
                break;
            case Constant::FORMAT_BARRIER:
                $result .= "barrier";
                break;
            case Constant::FORMAT_REQUEST:
                $result .= "request";
                break;
            case Constant::FORMAT_CONTRACT:
                $result .= "contract";
                break;
            default:

                break;
        }
        return $result;
    }

    private function count($id, $choices, $voies){
        if(key_exists($id, $voies)){
            $s1 = 0;
            $s2 = 0;
            $s3 = 0;
            $s4 = 0;
            foreach($voies as $inter){
                switch ($inter) {
                    case 1:
                        $s1++;
                        break;
                    case 2:
                        $s2++;
                        break;
                    case 3:
                        $s3++;
                        break;
                    case 4:
                        $s4++;
                        break;
                    default:
                        // code...
                        break;
                };
            }
            $length = sizeof($voies);
            return [$s1*100/$length, $s2*100/$length, $s3*100/$length, $s4*100/$length];
        }else{
            $length = 100/sizeof($choices);
            return [$length, $length, $length, $length];
        }
    }

    private function choices($choices, $size){
        $result = "";
        $color = ["#2A2A2A", "#272727", "#242424", "#212121"];
        $sign = ["X", "O", "△", "◻"];
        $length = sizeof($choices);
        $i=0;
        while($i < $length and $i <  4){
            $result .=
            '<div rep="'.($i+1).'" class="choice alignement" style="width: '.$size[$i].'%; background: '.$color[$i].';">
                '.$sign[$i].'
            </div>';
            $i++;
        }
        return $result;
    }

    private function choicesNames(array $choices){
        $result = "";
        $sign = ["X", "O", "△", "◻"];
        for($i=0; $i<sizeof($choices); $i++)
            $result .= $sign[$i].": ".$choices[$i]."<br/>";
        return $result;
    }
}
