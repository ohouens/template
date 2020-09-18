<?php
class Katalogi{
    public static function distance($lat1, $long1, $lat2, $long2){
    	$result = 6367445*acos(sin(deg2rad($lat1))*sin(deg2rad($lat2))+cos(deg2rad($lat1))*cos(deg2rad($lat2))*cos(deg2rad($long1-$long2)));
    	//echo 'lat1 '.$lat1.'<br/>long1 '.$long1.'<br/>lat2 '.$lat2.'<br/>long2 '.$long2.'<br/>distance: '.$result.'m<br/><br/>';
    	return $result;
    }

    public static function catalogue($bdd, $id){
    	$tab=[];
    	$i=0;
    	$req = $bdd->query('SELECT id FROM onisowo_groupe WHERE nom = "Catalogue"');
    	$inter = $req->fetch();
    	if(!$inter)return 13;
    	$req = $bdd->query('SELECT * FROM onisowo_demande WHERE visible = 1 ORDER BY id DESC');
    	$final = $req->fetchAll();
    	if(!$final)return 14;
    	foreach($final as $fini){
    		//verification si la demande fait partie du catalogue
    		if(preg_match("#^".$inter['id']."\|1\|0$#", $fini['type'])){
    			$demande = explode('|', $fini['demande']);
    			if(count($demande > 3)){
    				//creation de la demande
    				$tab[$i]["num"] = $fini['id'];
    				$tab[$i]["titre"] = $demande[0];
    				$tab[$i]["description"] = $demande[1];
    				if(substr($demande[count($demande)-1], -4) == ".png" or substr($demande[count($demande)-1], -4) == ".jpg" or substr($demande[count($demande)-1], -5) == ".jpeg")
    					$tab[$i]["image"] = $demande[count($demande)-1];
    				for($y = 0; $y<count($demande); $y++){
    					if(preg_match("#:#", $demande[$y])){
    						$actuel = explode(":", $demande[$y],2);
    						$tab[$i][$actuel[0]] = $actuel[1];
    					}
    				}
    				//verification avant ajout de la demande
    				//verification ports indispensables
    				if(isset($tab[$i]["type"])){
    					//verification type "lecturre"
    					if(isStatic($tab[$i]["type"])){
    						if(isset($tab[$i]["prix"])){
    							if(preg_match("#^[0-9]$#",$tab[$i]["prix"]))$i++;
    							else unset($tab[$i]);
    						}else unset($tab[$i]);
    					}else unset($tab[$i]);
    				}else unset($tab[$i]);
    			}
    		}
    	}
    	$fini = array(
    		"taux" => getTaux(),
    		"pseudo" => get($bdd, $id, 'pseudo'),
    		"points" => get($bdd, $id, 'point'),
    		"annonces" => $tab
    	);
    	return json_encode($fini);
    }

    function cataloguePosition($bdd, $id, $lat, $long){
    	$distances = array(20, 50, 75, 100, 300, 500, 1000, 8000);
    	$tab = array();
    	$i=0;
    	$a=0;
    	$req = $bdd->query('SELECT id FROM onisowo_groupe WHERE nom = "Catalogue"');
    	$inter = $req->fetch();
    	if(!$inter)return 13;
    	$req = $bdd->query('SELECT * FROM onisowo_demande WHERE visible = 1 ORDER BY id DESC');
    	$final = $req->fetchAll();
    	if(!$final)return 14;
    	//ajout des demandes par distances croissantes
    	while(count($tab)<15 and $a<count($distances)){
    		foreach($final as $fini){
    			//verification si la demande n'a pas été déja ajouté
    			for($b=0; $b<count($tab); $b++)
    				if(in_array($fini['id'], $tab[$b]))continue 2;
    			//verification si la demande fait partie du catalogue
    			if(preg_match("#^".$inter['id']."\|1\|0$#", $fini['type'])){
    				$demande = explode('|', $fini['demande']);
    				if(count($demande > 3)){
    					//creation de la demande
    					$tab[$i]["num"] = $fini['id'];
    					$tab[$i]["titre"] = $demande[0];
    					$tab[$i]["description"] = $demande[1];
    					if(substr($demande[count($demande)-1], -4) == ".png" or substr($demande[count($demande)-1], -4) == ".jpg" or substr($demande[count($demande)-1], -5) == ".jpeg")
    						$tab[$i]["image"] = $demande[count($demande)-1];
    					for($y = 0; $y<count($demande); $y++){
    						if(preg_match("#:#", $demande[$y])){
    							$actuel = explode(":", $demande[$y], 2);
    							$tab[$i][$actuel[0]] = $actuel[1];
    						}
    					}
    					//verification avant ajout de la demande
    					//verification ports indispensables
    					if(isset($tab[$i]["lat"]) and isset($tab[$i]["long"]) and isset($tab[$i]["type"])){
    						//echo $tab[$i]["titre"].':<br/>';
    						if(distance($lat, $long, $tab[$i]["lat"], $tab[$i]["long"]) <= $distances[$a]){
    							//verification type "lecturre"
    							if($tab[$i]["type"] == 0)$i++;
    							else{
    								if(isset($tab[$i]["prix"])){
    									if(preg_match("#^[0-9]$#",$tab[$i]["prix"]))$i++;
    									else unset($tab[$i]);
    								}else unset($tab[$i]);
    							}
    						}else unset($tab[$i]);
    					}else unset($tab[$i]);
    				}
    			}
    		}
    		$a++;
    	}
    	//Transformation des valeurs de retour sous format json
    	$fini = array(
    		"taux" => getTaux(),
    		"pseudo" => get($bdd, $id, 'pseudo'),
    		"points" => get($bdd, $id, 'point'),
    		"annonces" => $tab
    	);
    	return json_encode($fini);
    }
}
