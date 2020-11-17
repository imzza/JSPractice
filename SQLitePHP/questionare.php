<?php 
// http://zetcode.com/php/sqlite3/
// https://riptutorial.com/php/example/27461/sqlite3-quickstart-tutorial
// required headers
//header("Access-Control-Allow-Origin: *");
//
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: *"); //Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"


function getUserIp(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function getConnection(){
    $database_name = "questionare.db";
    $db = new SQLite3($database_name);
    return $db;
}

$db = getConnection();
// $version = $db->querySingle('SELECT SQLITE_VERSION()');
// echo $version . "\n";




// get the Last Query 
// $last_row_id = $db->lastInsertRowID();
// echo "The last inserted row Id is $last_row_id";

// Escaped Example
// $sql = "SELECT name FROM cars WHERE name = 'Audi'";
// $escaped = SQLite3::escapeString($sql);


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action =='get') {
        $query = "SELECT rowid, * FROM questions";
        if (isset($_GET['mid']) && $_GET['mid'] == 0) {
           $query.= " ORDER BY ID ASC LIMIT 1";  
        }else{
            $query.= " WHERE id = ".SQLite3::escapeString($_GET['mid'])." LIMIT 1";
        }
        
        $result = $db->querySingle($query, true);

        if($result == FALSE){
            http_response_code(500);
            echo json_encode(['message' => $db->lastErrorMsg()]);
        }
        
        if($result['qtype'] == 'options'){
            $opts = $db->query("SELECT * FROM question_options WHERE qid=".$result['id']);
            $data = [];
            while ($row = $opts->fetchArray(SQLITE3_ASSOC)) {
                 $data[] = $row;
            }
            $result['options'] = $data;
        }

        // $result = $db->query($query);
        // $data = [];
        // while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        //     $data[] = $row;
        // }
        
        http_response_code(200);
        echo json_encode($result);
    } else if($action == 'save'){
        $data = file_get_contents('php://input');
        $data = json_decode($data, JSON_OBJECT_AS_ARRAY);
        $questions = $data['question'];

        $ip = getUserIp();
        $api_key = SQLite3::escapeString($data['apikey']);
        $date = SQLite3::escapeString(date('Y-m-d H:i:s'));

        $insert = "INSERT INTO questionare_response (API_KEY,user_ip,created_at) VALUES('$api_key', '$ip', '$date');";
        $result = $db->query($insert);
        if ($result){
            $resp_id  = $db->lastInsertRowID();

            foreach($questions as $key => $question){
                $q = $db->escapeString($question['title']);
                $a = $db->escapeString($question['selected']);
                $d = date('Y-m-d H:i:s');

                $ins = "INSERT INTO response_questions (resp_id, question, answer, created_at) VALUES ('$resp_id', '$q', '$a', '$d');";
                $db->exec($ins);
            }

            http_response_code(201);
            echo json_encode(['message' => 'Submitted Successfully', 'success' => true]);
            die();
        }else{
            http_response_code(400);
            echo josn_encode(['message' => $db->lastErrorMsg()]);
            die();
        }
    }
}else{
    http_response_code(200);
    echo json_encode(array('message' => 'Please specify a action'));
    die();
}

?>