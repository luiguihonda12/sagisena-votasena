<?php
require_once('models/conexion.php');

class Votmvvc {
    private $idusu;
    private $canusu;
    private $dtvot;

    // Métodos Get
    public function getIdusu() {
        return $this->idusu;
    }

    public function getCanusu() {
        return $this->canusu;
    }

    public function getDtvot() {
        return $this->dtvot;
    }

    // Métodos Set
    public function setIdusu($idusu) {
        $this->idusu = $idusu;
    }

    public function setCanusu($canusu) {
        $this->canusu = $canusu;
    }

    public function setDtvot($dtvot) {
        $this->dtvot = $dtvot;
    }

    // Método para obtener la ficha del usuario actual
    public function getFichaUsuario($idusu) {
        if (!$idusu) {
            return null;
        }
        try {
            $sql = "SELECT uf.idfic FROM usufic uf WHERE uf.idusu = :idusu AND uf.actfic = '1' LIMIT 1";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $result->bindParam(":idusu", $idusu, PDO::PARAM_INT);
            $result->execute();
            $res = $result->fetch(PDO::FETCH_ASSOC);
            return $res ? $res['idfic'] : null;
        } catch (PDOException $e) {
            error_log("Error en getFichaUsuario: " . $e->getMessage());
            return null;
        }
    }

    // Método para obtener los voceros postulados de la misma ficha
    public function getVocerosMismaFicha($idfic, $idusu_actual = null) {
        if (!$idfic) {
            return [];
        }
        try {
            $sql = "SELECT DISTINCT u.idusu, u.nomusu, u.fotcan, u.noca, f.nomfic, uf.idfic
                    FROM usuario u
                    INNER JOIN usupef up ON u.idusu = up.idusu
                    INNER JOIN usufic uf ON u.idusu = uf.idusu
                    INNER JOIN ficha f ON uf.idfic = f.idfic
                    WHERE up.idper = 13
                    AND u.actusu = '1'
                    AND uf.idfic = :idfic
                    AND (:idusu_actual IS NULL OR u.idusu != :idusu_actual)
                    ORDER BY CAST(NULLIF(u.noca, '') AS UNSIGNED) ASC, u.nomusu ASC";
            
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":idfic", $idfic);
            $stmt->bindParam(":idusu_actual", $idusu_actual);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getVocerosMismaFicha: " . $e->getMessage());
            return [];
        }
    }

    // Método para obtener todos los voceros registrados (cuando el usuario no tiene ficha)
    public function getAllVoceros($idcen = null, $idusu_actual = null) {
        try {
            $sql = "SELECT u.idusu, u.nomusu, u.fotcan, u.noca, u.emausu, u.telcan,
                           uf.idfic, f.nomfic, v.nomval AS jornada, c.nomcen
                    FROM usuario u
                    INNER JOIN usupef up ON u.idusu = up.idusu
                    INNER JOIN centro c ON u.idcen = c.idcen
                    LEFT JOIN usufic uf ON u.idusu = uf.idusu AND uf.actfic = 1
                    LEFT JOIN ficha f ON uf.idfic = f.idfic
                    LEFT JOIN valor v ON f.jornada = v.idval
                    WHERE up.idper = 13
                    AND u.actusu = '1'
                    AND (:idusu_actual IS NULL OR u.idusu != :idusu_actual)";

            $params = [':idusu_actual' => $idusu_actual];
            if ($idcen) {
                $sql .= " AND u.idcen = :idcen";
                $params[':idcen'] = $idcen;
            }
            $sql .= " GROUP BY u.idusu ORDER BY u.noca";

            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $stmt = $conexion->prepare($sql);
            foreach ($params as $clave => $valor) {
                $tipo = is_null($valor) ? PDO::PARAM_NULL : PDO::PARAM_STR;
                $stmt->bindValue($clave, $valor, $tipo);
            }
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getAllVoceros: " . $e->getMessage());
            return [];
        }
    }

    // Método para consultar si el usuario ya votó por vocero
    public function getOne($tipo = 'vocero') {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $idusu = $this->getIdusu();

            if (!$idusu) {
                return false;
            }

            // Intentar consulta con columna tipo_voto
            try {
                $sql = "SELECT COUNT(*) AS co FROM voto WHERE idusu = :idusu AND tipo_voto = :tipo";
                $result = $conexion->prepare($sql);
                $result->bindParam(":idusu", $idusu, PDO::PARAM_INT);
                $result->bindParam(":tipo", $tipo, PDO::PARAM_STR);
                $result->execute();
                $res = $result->fetch(PDO::FETCH_ASSOC);
                return ($res && $res['co'] > 0);
            } catch (PDOException $ex) {
                // Fallback si no existe columna tipo_voto
                $sql = "SELECT COUNT(*) AS co FROM voto WHERE idusu = :idusu";
                $result = $conexion->prepare($sql);
                $result->bindParam(":idusu", $idusu, PDO::PARAM_INT);
                $result->execute();
                $res = $result->fetch(PDO::FETCH_ASSOC);
                return ($res && $res['co'] > 0);
            }
        } catch (PDOException $e) {
            error_log("Error en getOne (vocero): " . $e->getMessage());
            return false;
        }
    }

    // Método para registrar el voto
    public function save($tipo = 'vocero') {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            
            if ($this->getOne($tipo)) {
                error_log("Usuario ya votó por $tipo: " . $this->idusu);
                return false;
            }
            
            // Intentar inserción con tipo_voto
            try {
                $sql = "INSERT INTO voto (idusu, canusu, dtvot, tipo_voto) 
                        VALUES (:idusu, :canusu, :dtvot, :tipo)";
                $result = $conexion->prepare($sql);
                $result->bindParam(":idusu", $this->idusu, PDO::PARAM_INT);
                $result->bindParam(":canusu", $this->canusu, PDO::PARAM_INT);
                $result->bindParam(":dtvot", $this->dtvot);
                $result->bindParam(":tipo", $tipo, PDO::PARAM_STR);
                return $result->execute();
            } catch (PDOException $ex) {
                // Fallback si la tabla voto no tiene la columna tipo_voto
                $sql = "INSERT INTO voto (idusu, canusu, dtvot) 
                        VALUES (:idusu, :canusu, :dtvot)";
                $result = $conexion->prepare($sql);
                $result->bindParam(":idusu", $this->idusu, PDO::PARAM_INT);
                $result->bindParam(":canusu", $this->canusu, PDO::PARAM_INT);
                $result->bindParam(":dtvot", $this->dtvot);
                return $result->execute();
            }
            
        } catch (PDOException $e) {
            error_log("Error en save voto $tipo: " . $e->getMessage());
            return false;
        }
    }

    // Obtener información del voto realizado por el usuario
    public function getVotoUsuario($tipo = 'vocero') {
        try {
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $idusu = $this->getIdusu();
            
            if (!$idusu) {
                return null;
            }

            try {
                $sql = "SELECT v.*, u.nomusu, u.fotcan, f.nomfic 
                        FROM voto v 
                        LEFT JOIN usuario u ON v.canusu = u.idusu 
                        LEFT JOIN usufic uf ON u.idusu = uf.idusu 
                        LEFT JOIN ficha f ON uf.idfic = f.idfic 
                        WHERE v.idusu = :idusu AND v.tipo_voto = :tipo
                        ORDER BY v.dtvot DESC LIMIT 1";
                
                $result = $conexion->prepare($sql);
                $result->bindParam(":idusu", $idusu, PDO::PARAM_INT);
                $result->bindParam(":tipo", $tipo, PDO::PARAM_STR);
                $result->execute();
                return $result->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $ex) {
                $sql = "SELECT v.*, u.nomusu, u.fotcan, f.nomfic 
                        FROM voto v 
                        LEFT JOIN usuario u ON v.canusu = u.idusu 
                        LEFT JOIN usufic uf ON u.idusu = uf.idusu 
                        LEFT JOIN ficha f ON uf.idfic = f.idfic 
                        WHERE v.idusu = :idusu 
                        ORDER BY v.dtvot DESC LIMIT 1";
                
                $result = $conexion->prepare($sql);
                $result->bindParam(":idusu", $idusu, PDO::PARAM_INT);
                $result->execute();
                return $result->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            error_log("Error en getVotoUsuario: " . $e->getMessage());
            return null;
        }
    }

    // Obtener la jornada de la ficha del aprendiz
    public function getOneJor() {
        try {
            $sql = "SELECT f.jornada FROM usufic AS u INNER JOIN ficha AS f ON u.idfic=f.idfic WHERE u.actfic ='1' AND u.idusu=:idusu LIMIT 1;";
            $modelo = new conexion();
            $conexion = $modelo->get_conexion();
            $result = $conexion->prepare($sql);
            $idusu = $this->getIdusu();
            $result->bindParam(":idusu", $idusu, PDO::PARAM_INT);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getOneJor: " . $e->getMessage());
            return [];
        }
    }
}

// Alias de clase para compatibilidad total
if (!class_exists('Mvvc')) {
    class Mvvc extends Votmvvc {}
}
?>