<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
/*
Home:
'/'   = login

Listar:
$app->get('/cmo/veiculo/',
$app->get('/cmo/cliente/',

Inserir:
$app->post('/cmo/veiculo/[{veiculo}]'                              
$app->post('/cmo/cliente/[{cliente}]'

Deletar:
$app->delete('/cmo/veiculo/delete/[{placa}]'
$app->delete('/cmo/cliente/delete/[{cpf_cnpj}]'

Update
$app->put('/cmo/veiculo/update/[{veiculo}]'
$app->put('/cmo/cliente/update/[{cliente}]'

Buscar
$app->get('/cmo/veiculo/buscar/[{placa}]'
$app->get('/cmo/cliente/buscar/[{cpf_cnpj}]'

*/
return function ($app, $container) {

    // All individual entities
    $app->get('/veiculo/', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [GET] pessoas/");

        $connection = $this->db;
        $stmt = $connection->query("SELECT * FROM veiculo order by cod_veiculo desc;");

        $data = $stmt->fetchAll();

        return $response->withJson($data)->withStatus(200);
    });

    $app->get('/veiculo/placa/[{placa}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [GET] pessoas/");
        $placa = $args{'placa'};
     //   var_dump($placa); die;
        $connection = $this->db;
        $stmt = $connection->query("SELECT * FROM veiculo where placa like '%" . $placa ."%' order by cod_veiculo desc;");

        $data = $stmt->fetchAll();

        return $response->withJson($data)->withStatus(200);
    });


    $app->get('/veiculo/fabricante/[{fabricante}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [GET] pessoas/");
        $fabricante = $args{'fabricante'};
        $connection = $this->db;
        $stmt = $connection->query("SELECT * FROM fabricante where nome_fabricante  like '%" . $fabricante ."%';");

        $data = $stmt->fetchAll();

        return $response->withJson($data)->withStatus(200);
    });


    $app->post('/veiculo/add', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [POST] pessoas/. Received Data: " . json_encode($args));
        $body = $request->getParsedBody();
        $connection = $this->db;
        $data = null;
        $placa = $request->getParam('placa');
        $km = $request->getParam('km');
        $obs= $request->getParam('obs');
        $modelo= $request->getParam('modelo');
        $cod_fabricante= $request->getParam('cod_fabricante');
        $cod_cliente = $request->getParam('cod_cliente');
        $sql = "INSERT INTO veiculo (placa, km, obs, modelo, cod_fabricante, cod_cliente) VALUES (:placa, :km, :obs, :modelo, :cod_fabricante, :cod_cliente);";
        
       try {
            
            $stmt = $connection->prepare($sql);

            $stmt->bindParam(':placa',$placa);
            $stmt->bindParam(':km',$km);
            $stmt->bindParam(':obs',$obs);
            $stmt->bindParam(':modelo',$modelo);
            $stmt->bindParam(':cod_fabricante', $cod_fabricante);
            $stmt->bindParam(':cod_cliente', $cod_cliente);

            $stmt->execute();
            echo '{"notice": {"text":"Veiculo adicionado com sucesso."}';

        } catch (PDOException $e) {
            echo '{"error": {"text": ' .$e->getMessage().'}';
        }

});


$app->put('/veiculo/update/{id}', function (Request $request, Response $response) use ($container) {
    // Sample log message
    $body = $request->getParsedBody();
    $connection = $this->db;
    $id = $request->getAttribute('id');
    $container->get('logger')->info("Pessoas App: [PUT] veiculo/. Received Data: " . json_encode($idv));
    $placa = $request->getParam('placa');
    $km = $request->getParam('km');
    $obs= $request->getParam('obs');
    $modelo= $request->getParam('modelo');
    $cod_fabricante= $request->getParam('cod_fabricante');
    $cod_cliente = $request->getParam('cod_cliente');

    $sql = "UPDATE veiculo SET placa = :placa, km = :km , obs = :obs , modelo = :modelo , cod_fabricante = :cod_fabricante, cod_cliente = :cod_cliente where cod_veiculo = $id";
    
   try {
        
        $stmt = $connection->prepare($sql);

        $stmt->bindParam(':placa',$placa);
        $stmt->bindParam(':km',$km);
        $stmt->bindParam(':obs',$obs);
        $stmt->bindParam(':modelo',$modelo);
        $stmt->bindParam(':cod_fabricante', $cod_fabricante);
        $stmt->bindParam(':cod_cliente', $cod_cliente);

        $stmt->execute();
        echo '{"notice": {"text":"Veiculo atualizado com sucesso."}';

    } catch (PDOException $e) {
        echo '{"error": {"text": ' .$e->getMessage().'}';
    }

});


$app->delete('/veiculo/delete/{id}', function (Request $request, Response $response, array $args) use ($container) {
    // Sample log message
    $container->get('logger')->info("Pessoas App: [PUT] veiculo/. Received Data: " . json_encode($args));
    $body = $request->getParsedBody();
    $connection = $this->db;
    $id = $request->getAttribute('id');
    
    
    $sql = "DELETE from veiculo where cod_veiculo = $id ;";
   try {
        
        $stmt = $connection->prepare($sql);
        
        $stmt->execute();
        echo '{"notice": {"text":"Veiculo retirado do registro com sucesso."}';

    } catch (PDOException $e) {
        echo '{"error": {"text": ' .$e->getMessage().'}';
    }
});


};