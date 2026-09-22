<?php
require_once "models/conexion.php";

class Votmact{

	function selAll($fidcen = null, $fidjor = null){
		try{
            $sql = "SELECT u.idusu, u.ndocusu, u.nomusu, up.idper, f.jornada, u.emausu, u.idcen, c.nomcen, u.actusu, u.fotcan, u.telcan, u.noca 
                    FROM usuario AS u 
                    LEFT JOIN usupef AS up ON u.idusu=up.idusu 
                    LEFT JOIN usufic AS uf ON u.idusu=uf.idusu 
                    LEFT JOIN ficha as f ON f.idfic=uf.idfic 
                    LEFT JOIN centro AS c ON u.idcen=c.idcen 
                    LEFT JOIN valor AS v ON f.jornada=v.idval 
                    WHERE up.idper=3";

            if ($fidcen) {
                $sql .= " AND u.idcen = :fidcen";
            }
            if ($fidjor) {
                $sql .= " AND f.jornada = :fidjor";
            }

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            
            if ($fidcen) {
                $result->bindParam(':fidcen', $fidcen);
            }
            if ($fidjor) {
                $result->bindParam(':fidjor', $fidjor);
            }

            $result->execute();
            $res = $result->fetchall(PDO::FETCH_ASSOC);
            
            // Ordenar por votos, reutilizando nvoCan
            foreach ($res as $key => $row) {
                $votos = $this->nvoCan($row['idusu']);
                $res[$key]['total_votos'] = isset($votos[0]['nvo']) ? (int)$votos[0]['nvo'] : 0;
            }
            
            usort($res, function($a, $b) {
                return $b['total_votos'] <=> $a['total_votos'];
            });

            return $res;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}

    function getJor(){
		try{
            $sql = "SELECT idval, nomval FROM valor WHERE iddom=1";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->execute();
            $res = $result->fetchall(PDO::FETCH_ASSOC);
            return $res;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}

	function nvoCan($canusu){
		try {
            $sql = "SELECT count(idusu) AS nvo FROM voto WHERE canusu=:canusu";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":canusu", $canusu);
            $result->execute();
            $res = $result->fetchall(PDO::FETCH_ASSOC);
            return $res;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
}
?>
