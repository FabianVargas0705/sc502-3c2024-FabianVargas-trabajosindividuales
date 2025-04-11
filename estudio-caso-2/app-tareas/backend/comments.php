<?php
require('db.php');

function createComment($id,$task_id,$comment)
{
    global $pdo;
    try {
        $sql = "insert into comments (id,task_id,comment) value (:id,:task_id,:comment)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => getUltimoId(),
            'task_id' => $task_id,
            'comment' => $comment
        ]);
        return $pdo->lastInsertId();

    } catch (Exception $e) {
        echo $e->getMessage();
        return 0;
    }
}

function getUltimoId(){
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT MAX(id) as id FROM comments");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'] + 1;
    } catch (Exception $e) {
        echo $e->getMessage();
        return 0;
    }
}
function getCommentsByTask($task_id)
{
    try {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM comments where task_id = :task_id");
        $stmt->execute(['task_id' => $task_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        echo "Error al obtener los comentarios de la tarea" . $ex->getMessage();
        return [];
    }
}

function editComment($id, $comment)
{
    global $pdo;
    try {
        $sql = "update comments set comment = :comment where id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'comment' => $comment,
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        echo $e->getMessage();
        return false;
    }
}

function deleteComment($id)
{
    global $pdo;
    try {
        $sql = "delete from comments where id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        echo $e->getMessage();
        return false;
    }
}

$method = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');

function getJsonInput()
{
    return json_decode(file_get_contents("php://input"), associative: true);
}

session_start();

if (isset($_SESSION["user_id"])) {
    try {
        $userId = $_SESSION["user_id"];

        switch($method) {
           case 'GET':
                if (isset($_GET['task_id'])) {
                    $task_id = $_GET['task_id'];
                    $comments = getCommentsByTask($task_id);
                    echo json_encode($comments);
                } else {
                    echo json_encode(["error" => "No se ha proporcionado el id de la tarea"]);
                }
                break;
            case 'POST':
                $input = getJsonInput();
                if (isset($input['task_id'], $input['comment'])) {
                    $task_id = $input['task_id'];
                    $comment = $input['comment'];
                    $result = createComment($id, $task_id, $comment);
                    echo json_encode(["id" => $result]);
                } else {
                    echo json_encode(["error" => "Faltan datos para crear el comentario"]);
                }
                break;
            case 'PUT':
                $input = getJsonInput();
                if (isset($input['id'], $input['comment'])) {
                    $id = $input['id'];
                    $comment = $input['comment'];
                    if (editComment($id, $comment)) {
                        echo json_encode(["success" => true]);
                    } else {
                        echo json_encode(["error" => "No se pudo editar el comentario"]);
                    }
                } else {
                    echo json_encode(["error" => "Faltan datos para editar el comentario"]);
                }
                break;
            case 'DELETE':
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    if (deleteComment($id)) {
                        echo json_encode(["success" => true]);
                    } else {
                        echo json_encode(["error" => "No se pudo eliminar el comentario"]);
                    }
                } else {
                    echo json_encode(["error" => "Faltan datos para eliminar el comentario"]);
                }
                break;
            default:
                http_response_code(405);
                break;     

        }
    } catch (Exception $ex) {
        echo "Error al obtener el usuario" . $ex->getMessage();
    }
    
} else {
    $userId = null;
}

?>