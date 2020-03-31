<?php
error_reporting(E_ALL & ~E_NOTICE); //& ~E_NOTICE
ini_set('display_errors', 1);
//header('Access-Control-Allow-Origin: http://localhost');
//die('aaaa');
//including the required files
require_once 'include/Class.DbOperation.php';
//All Files	C:\xampp\htdocs\parkin\index.php
require 'vendor/autoload.php';


$app = new \Slim\App();
//echo "<pre>"; print_r($app); die('aaq');
//Method to display response
function echoResponse($status_code, Slim\Http\Response $res, $post) {
    $responsenew = $res->withStatus($status_code);
    return $responsenew->withHeader('Content-Type', 'application/json;charset=utf-8')
                    ->write(json_encode($post, JSON_UNESCAPED_UNICODE));
}

function verifyRequiredParams($response, $required_fields) {
    //Assuming there is no error
    $error = false;
    //Error fields are blank
    $error_fields = "";
    //Getting the request parameters
    $request_params = $_REQUEST;
     //echo "<pre>"; print_r($required_fields);print_r($request_params); die;
    //Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        //Getting the app instance
        $app = \Slim\Slim::getInstance();
        //Getting put parameters in request params variable
        parse_str($app->request()->getBody(), $request_params);
    }

    //Looping through all the parameters
    foreach ($required_fields as $field) {
        //if any requred parameter is missing
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            //error is true
            $error = true;
            //Concatnating the missing parameters in error fields
            $error_fields .= $field . ', ';
        }
    }

    //if there is a parameter missing then error is true
    if ($error) {
        //Creating response array
        $res = array();
        
        $message = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        
    }else{
        $message = true;
    }
    return $message;
}

//Method to authenticate a student 

function validApiKey(\Slim\Http\Request $request, Slim\Http\Response $response, $args) {
    $requestDataHeader = getAllHeaders();
    
  
   return isset($requestDataHeader['Api']) && APIKEY == $requestDataHeader['Api'];
}

$app->post('/login', function (\Slim\Http\Request $request, Slim\Http\Response $response, $args) use ($app) {
    if (validApiKey($request, $response, $args)) {        
        //$reqFields = array('email', 'password');
        $valid = true;//verifyRequiredParams($response, $reqFields);
        if($valid === true){
            $db = new DbOperation();
            $returnData = $db->login($request, $response);
            return echoResponse(200, $response, $returnData);
        }else{
            $post["status"] = false;
            $post["message"] = $valid;
            return echoResponse(400, $response, $post);
        }
        
    } else {
        $post["status"] = false;
        $post["message"] = "Access Denied. Invalid Api key";
        return echoResponse(401, $response, $post);
    }
});
$app->get('/homepage/{user_id}', function (\Slim\Http\Request $request, Slim\Http\Response $response, $args) use ($app) {
    if (validApiKey($request, $response, $args)) {
            $db = new DbOperation();
            $returnData = $db->homePage($request, $response, $args);
            return echoResponse(200, $response, $returnData);
    } else {
        $post["status"] = false;
        $post["message"] = "Access Denied. Invalid Api key";
        return echoResponse(401, $response, $post);
    }
});
$app->post('/cms_engine_call', function (\Slim\Http\Request $request, Slim\Http\Response $response, $args) use ($app) {
  //print_r($request); die('aaaaa');
  //if (validApiKey($request, $response, $args)) {
            $db = new DbOperation(); // print_r($db);
            $returnData = $db->cms_engine_call($request, $response);
            return echoResponse(200, $response, $returnData);
    /*} else {
        $post["status"] = false;
        $post["message"] = "Access Denied. Invalid Api key";
        return echoResponse(401, $response, $post);
    }*/
});

$app->post('/jackpot_ui_call', function (\Slim\Http\Request $request, Slim\Http\Response $response, $args) use ($app) {
  //print_r($request); die('aaaaa');
  //if (validApiKey($request, $response, $args)) {
            $db = new DbOperation(); // print_r($db);
            $returnData = $db->jackpot_ui_call($request, $response);
            return echoResponse(200, $response, $returnData);
    /*} else {
        $post["status"] = false;
        $post["message"] = "Access Denied. Invalid Api key";
        return echoResponse(401, $response, $post);
    }*/
});
$app->post('/jackpot_player_call', function (\Slim\Http\Request $request, Slim\Http\Response $response, $args) use ($app) {
  //print_r($request); die('aaaaa');
  //if (validApiKey($request, $response, $args)) {
            $db = new DbOperation(); // print_r($db);
            $returnData = $db->jackpot_player_call($request, $response);
            return echoResponse(200, $response, $returnData);
    /*} else {
        $post["status"] = false;
        $post["message"] = "Access Denied. Invalid Api key";
        return echoResponse(401, $response, $post);
    }*/
});

$app->post('/convert_win_credit', function (\Slim\Http\Request $request, Slim\Http\Response $response, $args) use ($app) {
            $db = new DbOperation(); // print_r($db);
            $returnData = $db->convert_win_credit($request, $response);
            return echoResponse(200, $response, $returnData);

});
$app->run();
