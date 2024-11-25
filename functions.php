<?php
function incrementarVisitasArbol($arbol_id) {
    require_once("dtbconnection.php");
    global $conn;

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("SELECT visitas FROM arboles WHERE id_arbol = :arbol_id");
        $stmt->bindParam(':arbol_id', $arbol_id, PDO::PARAM_INT);
        $stmt->execute();
        $visitas = $stmt->fetchColumn();

        $visitas++;

        $stmt = $conn->prepare("UPDATE arboles SET visitas = :visitas WHERE id_arbol = :arbol_id");
        $stmt->bindParam(':visitas', $visitas, PDO::PARAM_INT);
        $stmt->bindParam(':arbol_id', $arbol_id, PDO::PARAM_INT);
        $stmt->execute();

        $conn->commit();
        return $visitas;
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Failed: " . $e->getMessage();
    }
    $conn = null;
}

function incrementarVisitasParque($parque_id) {
    require_once("dtbconnection.php"); 
  	global $conn;

    try {
        // Iniciar una transacción
        $conn->beginTransaction();
        
        // Obtener las visitas actuales
        $stmt = $conn->prepare("SELECT visitas FROM parques WHERE id_parque = :parque_id");
        $stmt->bindParam(':parque_id', $parque_id, PDO::PARAM_INT);
        $stmt->execute();
        $visitas = $stmt->fetchColumn();

        // Incrementar el contador de visitas
        $visitas++;

        // Actualizar el campo visitas en la tabla parques
        $stmt = $conn->prepare("UPDATE parques SET visitas = :visitas WHERE id_parque = :parque_id");
        $stmt->bindParam(':visitas', $visitas, PDO::PARAM_INT);
        $stmt->bindParam(':parque_id', $parque_id, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmar la transacción
        $conn->commit();

        // Devolver el nuevo número de visitas
        return $visitas;
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        echo "Failed: " . $e->getMessage();
    }
  $conn = null;
}

function loadArbolData($arbol_id){
    require_once("dtbconnection.php");
    global $conn; 

    try{
        $sqlArbolData="
            SELECT a.`nombre`, a.`nombre_cientifico`, a.`familia`, a.`clase`
            FROM `arboles` a
            WHERE a.`id_arbol` = :id;
        ";

        $stmt = $conn->prepare($sqlArbolData);
        $stmt->bindParam(':id', $arbol_id, PDO::PARAM_INT);
        $stmt->execute();

        $data_arbol = $stmt->fetch(PDO::FETCH_ASSOC);

        $sqlParquesRelacion="
            SELECT p.nombre, p.id_parque
            FROM relacion r
            INNER JOIN parques p ON r.id_parque = p.id_parque
            WHERE r.id_arbol = :id;
        ";

        $stmt = $conn->prepare($sqlParquesRelacion);
        $stmt->bindParam(':id', $arbol_id, PDO::PARAM_INT);
        $stmt->execute();

        $parques = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data_arbol['parques'] = $parques;
        incrementarVisitasArbol($arbol_id);

        return $data_arbol;
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
    $conn = null;
}

function loadParqueData($parque_id){
    require_once("dtbconnection.php");
    global $conn; 

    try{
        $sqlParqueData="
            SELECT p.`nombre`, p.`direccion`, p.`transporte_bus`, p.`transporte_metro`, p.`transporte_renfe`, p.`latitud`, p.`longitud`
            FROM `parques` p
            WHERE p.`id_parque` = :id;
        ";

        $stmt = $conn->prepare($sqlParqueData);
        $stmt->bindParam(':id', $parque_id, PDO::PARAM_INT);
        $stmt->execute();

        $data_parque = $stmt->fetch(PDO::FETCH_ASSOC);

        $sqlArbolesRelacion="
            SELECT a.nombre, a.id_arbol
            FROM relacion r
            INNER JOIN arboles a ON r.id_arbol = a.id_arbol
            WHERE r.id_parque = :id;
        ";

        $stmt = $conn->prepare($sqlArbolesRelacion);
        $stmt->bindParam(':id', $parque_id, PDO::PARAM_INT);
        $stmt->execute();

        $arboles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data_parque['arboles'] = $arboles;
        incrementarVisitasParque($parque_id);

        return $data_parque;
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
    $conn = null;
}

function loadTextParque($parque_id){
    require_once("dtbconnection.php");
    global $conn; 

    try{
        $sqlParqueContenido = "
            SELECT c.numero, c.titulo, c.titulo_en, c.texto, c.texto_en
            FROM contenido c WHERE c.id_referencia_p = :id
            ORDER BY c.numero;
        ";

        $stmt = $conn->prepare($sqlParqueContenido);
        $stmt->bindParam(':id',$parque_id, PDO::PARAM_INT);
        $stmt->execute();

        $contenido = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $contenido;
    } catch (PDOException $e){
        echo "Error de conexión: " . $e->getMessage();
    }
    $conn = null;
}

function loadTextArbol($arbol_id){
    require_once("dtbconnection.php");
    global $conn; 

    try{
        $sqlArbolContenido = "
            SELECT c.numero, c.titulo, c.titulo_en, c.texto, c.texto_en
            FROM contenido c WHERE c.id_referencia_a = :id
            ORDER BY c.numero;
        ";

        $stmt = $conn->prepare($sqlArbolContenido);
        $stmt->bindParam(':id',$arbol_id, PDO::PARAM_INT);
        $stmt->execute();

        $contenido = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $contenido;
    } catch (PDOException $e){
        echo "Error de conexión: " . $e->getMessage();
    }
    $conn = null;
}

function getAllParques(){
    require_once("dtbconnection.php");
    global $conn; 

    try{
        $sqlAllParques = "SELECT p.id_parque, p.nombre FROM parques p;";

        $stmt = $conn->prepare($sqlAllParques);
        $stmt->execute();

        $parques = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $parques;
    } catch (PDOException $e){
        echo "Error de conexión: " . $e->getMessage();
    }
    $conn = null;
}

function getAllArboles(){
    require_once("dtbconnection.php");
    global $conn; 

    try{
        $sqlAllArboles = "SELECT a.id_arbol, a.nombre FROM arboles a;";

        $stmt = $conn->prepare($sqlAllArboles);
        $stmt->execute();

        $arboles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $arboles;
    } catch (PDOException $e){
        echo "Error de conexión: " . $e->getMessage();
    }
    $conn = null;
}

function loginVerification($user, $pass){
    require_once("dtbconnection.php");
    global $conn;

    try {
        $loginCheck = "SELECT l.user_id, l.username, l.password FROM login l WHERE l.username = :user;";

        $stmt = $conn->prepare($loginCheck);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->execute();

        $login = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($login && password_verify($pass, $login['password'])) {  // Verificación segura de la contraseña
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
    $conn = null;
}


function loginData($user, $pass){
    require_once("dtbconnection.php");
    global $conn;

    try{
        $loginCheck = "SELECT l.user_id , l.username FROM login l WHERE l.username = :user AND l.password = :pass ;";

        $stmt = $conn->prepare($loginCheck);
        $stmt->bindParam(':user',$user, PDO::PARAM_STR);
        $stmt->bindParam(':pass',$pass, PDO::PARAM_STR);
        $stmt->execute();

        $login = $stmt->fetch(PDO::FETCH_ASSOC);

        return $login;
    } catch (PDOException $e){
        echo "Error de conexión: " . $e->getMessage();
    }
    $conn = null;
  }
}
?>
