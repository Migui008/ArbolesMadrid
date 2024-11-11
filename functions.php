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
    require_once("bbddconnect.php");
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
            SELECT p.nombre
            FROM relacion r
            INNER JOIN parques p ON r.id_parque = p.id_parque
            WHERE r.id_arbol = :id;
        ";

        $stmt = $conn->prepare($sqlParquesRelacion);
        $stmt->bindParam(':id', $arbol_id, PDO::PARAM_INT);
        $stmt->execute();

        $parques = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $data_arbol['parques'] = $parques;
        incrementarVisitasArbol($arbol_id);
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
    $conn = null;
}

function loadParqueData($parque_id){
    require_once("bbddconnect.php");
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
            SELECT a.nombre
            FROM relacion r
            INNER JOIN arboles p ON r.id_arbol = a.id_arbol
            WHERE r.id_parque = :id;
        ";

        $stmt = $conn->prepare($sqlArbolesRelacion);
        $stmt->bindParam(':id', $parque_id, PDO::PARAM_INT);
        $stmt->execute();

        $arboles = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $data_parque['arboles'] = $arboles;
        incrementarVisitasParque($parque_id);
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
    $conn = null;
}
