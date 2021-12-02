<?php
$hostname = "localhost";
$username = "id17917049_sinnombre";
$password = "Q}YXWfq6=Xf-F^u*";
$databaseName = "id17917049_wikiprofes20";

// connect to mysql database using mysqli
$connect = mysqli_connect($hostname, $username, $password, $databaseName);
$_SESSION['mysql_conn'] = $connect;

$user_id = $_SESSION["UserId"];

if (isset($_POST['action'])) {
    $post_id = $_POST['post_id'];
    $action = $_POST['action'];

    switch ($action) {
        case 'like':
            $sql = "INSERT INTO Reacciones (idUsuario, idComentario, reaccion) 
                   VALUES ($user_id, $post_id, 'like') 
                   ON DUPLICATE KEY UPDATE reaccion='like'";
            break;
        case 'dislike':
            $sql = "INSERT INTO Reacciones (idUsuario, idComentario, reaccion) 
                    VALUES ($user_id, $post_id, 'dislike') 
                    ON DUPLICATE KEY UPDATE reaccion='dislike'";
            break;
        case 'unlike':
            $sql = "DELETE FROM Reacciones WHERE idUsuario = $user_id AND idComentario = $post_id";
            break;
        case 'undislike':
            $sql = "DELETE FROM Reacciones WHERE idUsuario = $user_id AND idComentario = $post_id";
            break;
        default:
            break;
    }
    mysqli_query($_SESSION['mysql_conn'], $sql);
    echo getRating($post_id);
    exit(0);
}

function getRating($id)
{
    global $conn;
    $rating = array();
    $likes_query = "SELECT COUNT(*) FROM Reacciones WHERE idComentario = $id AND reaccion='like'";

    $dislikes_query = "SELECT COUNT(*) FROM Reacciones
                        WHERE idComentario = $id AND reaccion='dislike'";

    $likes_rs = mysqli_query($_SESSION['mysql_conn'], $likes_query);
    $dislikes_rs = mysqli_query($_SESSION['mysql_conn'], $dislikes_query);

    $likes = mysqli_fetch_array($likes_rs);
    $dislikes = mysqli_fetch_array($dislikes_rs);

    $rating = [
        'likes' => $likes[0],
        'dislikes' => $dislikes[0]
    ];
    return json_encode($rating);
}

function getLikes($id)
{
    global $conn;
    $sql = "SELECT COUNT(*) FROM Reacciones
  		  WHERE idComentario = $id AND reaccion = 'like'";

    $rs = mysqli_query($_SESSION['mysql_conn'], $sql);
    $result = mysqli_fetch_array($rs);
    return $result[0];
}

// Get total number of dislikes for a particular post
function getDislikes($id)
{
    global $conn;
    $sql = "SELECT COUNT(*) FROM Reacciones 
  		  WHERE idComentario = $id AND reaccion = 'dislike'";

    $rs = mysqli_query($_SESSION['mysql_conn'], $sql);
    $result = mysqli_fetch_array($rs);
    return $result[0];
}

function getDifference($nLikes, $nDislikes)
{
    return $nLikes - $nDislikes;
}

function userLiked($post_id)
{
    global $conn;
    global $user_id;
    $sql = "SELECT * FROM Reacciones WHERE idUsuario = $user_id 
  		  AND idComentario = $post_id AND reaccion = 'like'";

    $result = mysqli_query($_SESSION['mysql_conn'], $sql);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

// Check if user already dislikes post or not
function userDisliked($post_id)
{
    global $conn;
    global $user_id;
    $sql = "SELECT * FROM Reacciones WHERE idUsuario = $user_id 
  		  AND idComentario = $post_id AND reaccion = 'dislike'";
    $result = mysqli_query($_SESSION['mysql_conn'], $sql);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}
