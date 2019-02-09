<?php
class AdministrationWidget extends Widget{
    protected $_user;
    protected $_prompt;

    public function __construct(User $user){
        parent::__construct('', '', '', "administration", "", false, false);
        $this->_user = $user;
        $this->setSection(
            '<div id="action" class="alignement" style="width:80%;height: 100%;">
                '.$this->buildOptions().'
            </div><!--
            --><div id="menu" style="background: #2A2A2A;width:20%;height: 100%;" class="action alignement">
                <a href="#"><img src="style/icon/menu.png" alt="menu"/></a>
                <div class="plus">
                    '.$this->buildMenu().'
                </div>
            </div>'
        );
        $this->build();
    }

    public function getOptions(){
        /*récupérer toutes les options que l'user pourra utiliser dans son header.
        chercher les options dans les groupes de l'utilisateur, dans sa licence, dans ses accomplissements
        dans son store.
        Les options de bases sont:
            L'affichage du pseudo avec gérance du compte au clic(déconnexion, traduction, modifier profil, acces profil, acces statistiques),
            L'affichage du nombre de points avec gérance au clics(acheter, transférer, récupérer, rentrer code),
            L'affichage du titre du dernier thread modifier avec gérance au clic(creer un nouveau thread, modifier celui la, ajouter un elements)
            L'affichage du nombre de nouveau messages avec gérances au clic(boites de dialogues DM, nouveau message to:, creation groupe ephemere)
            L'affichage de la barre de recherche avec recherche en entrée(profil de groupe, profil d'utilisateur, thread, services, post, store)*/
        $result = [];
        array_push($result, 'ProfileOW');
        array_push($result, 'PointOW');
        array_push($result, 'ThreadOW');
        //array_push($result, 'GroupOW');
        //array_push($result, 'MessageOW');
        //array_push($result, 'SearchOW');
        return $result;
    }

    private function buildOptions(){
        $result = "";
        $options = $this->getOptions();
        foreach ($options as $option){
            $ow = new $option($this->_user);
            $result .= $ow->getContent();
        }
        return $result;
    }

    private function buildMenu(){
        $result = "";
        $options = $this->getOptions();
        foreach ($options as $option){
            $ow = new $option($this->_user);
            $result .= '<a href="index.php?'.$ow->getName().'" option="'.$ow->getName().'"><span>'.$ow->getName().'</span></a>
            ';
        }
        return $result;
    }

    public function getUser(){return $this->_user;}
    public function getPrompt(){return $this->_prompt;}

    public function setUser(User $user){
        $this->_user = $user;
    }

    public function setPrompt($prompt){
        if(!preg_match("#^.{0,77}$#", $prompt)){
            trigger_error("Prompt can't be more than 77 characters", E_USER_WARNING);
            return;
        }
        $this->_prompt = $prompt;
    }
}
