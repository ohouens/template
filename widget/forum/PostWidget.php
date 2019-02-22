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
