<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
 
include_once '../config/database.php';
include_once '../objects/utilisateur.php';
 
$database = new Database();
$db = $database->getConnection();

$utilisateur = new Utilisateur($db);
 
$data = json_decode($_GET["data"]) ;

if(!empty($data->email))
{
    $utilisateur->email = $data->email ;
    $stmt = $utilisateur->read_one();
    $num = $stmt->rowCount();
    
    if($num>0){
    
        $utilisateur=array();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ;
        
        extract($row);
    
        $utilisateur=array(
            "id" => $id ,
            "nom" => html_entity_decode($nom) ,
            "prenom" => html_entity_decode($prenom) ,
            "admin" => $admin ,
            "contact" => $contact ,
            "adresse" => html_entity_decode($adresse) ,
            "status" => $statut
        );
    
        http_response_code(200);
    
        echo json_encode($utilisateur);
    }
    else{
    
        http_response_code(201);
    
        echo json_encode(
            array("message" => "Cet email n'est pas associé à un compte")
        );
    }
}
else{

    http_response_code(404);
    
    echo json_encode(
        array("message" => "Pas assez de données")
    );
}