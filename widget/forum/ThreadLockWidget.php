<?php
class ThreadLockWidget extends Widget{
    protected $_post;

    public function __construct(Post $post){
        parent::__construct(
            "",
            '<div id="action_container" num="'.$post->getId().'">
                <div class="alignement previous"></div><!--
                --><div id="action_current" class="alignement">
                    '.$this->subConstruct($post).'
                </div><!--
                --><div class="alignement next"></div>
            </div>',
            "",
            "action",
            "",
            false,
            true
        );
        $this->_post = $post;
        $this->build();
    }

    private function subConstruct(Post $post){
        $result = "";
        switch($post->getFormat()){
            case Constant::FORMAT_VOTE:
                $result .= "vote";
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
}
