<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function ($app, $container) {

    // All individual entities
    $app->get('/cliente/', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [GET] pessoas/");

        $connection = $this->db;
        $stmt = $connection->query("SELECT * FROM cliente order by nome_cliente asc;");

        $data = $stmt->fetchAll();

        return $response->withJson($data)->withStatus(200);
    });



    $app->get('/cliente/nome/[{nome}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [GET] pessoas/");
        $nome = $args{'nome'};
     //   var_dump($placa); die;
        $connection = $this->db;
        $stmt = $connection->query("SELECT * FROM cliente where nome_cliente like '%" . $nome ."%' order by cod_cliente desc;");

        $data = $stmt->fetchAll();

        return $response->withJson($data)->withStatus(200);
    });


    $app->get('/cliente/email/[{email}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [GET] pessoas/");
        $email = $args{'email'};
     //   var_dump($placa); die;
        $connection = $this->db;
        $stmt = $connection->query("SELECT * FROM cliente where email like '%" . $email ."%' order by cod_cliente desc;");

        $data = $stmt->fetchAll();

        return $response->withJson($data)->withStatus(200);
    });


    $app->get('/cliente/cpf/[{cpf}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [GET] pessoas/");
        $cpf = $args{'cpf'};
     //   var_dump($placa); die;
        $connection = $this->db;
        $stmt = $connection->query("SELECT * FROM cliente where cpf_cnpj like '%" . $cpf ."%' order by cod_cliente desc;");

        $data = $stmt->fetchAll();

        return $response->withJson($data)->withStatus(200);
    });

    $app->post('/cliente/add', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [POST] pessoas/. Received Data: " . json_encode($args));
        $body = $request->getParsedBody();
        $connection = $this->db;
        $data = null;
        $email = $request->getParam('email');
        $cpf_cnpj = $request->getParam('cpf_cnpj');
        $senha= $request->getParam('senha');
        $telefone= $request->getParam('telefone');
        $nome_cliente= $request->getParam('nome_cliente');
        
        $sql = "INSERT INTO cliente (email, cpf_cnpj, senha, telefone, nome_cliente) VALUES (:email, :cpf_cnpj, :senha, :telefone, :nome_cliente);";
        
       try {
            
            $stmt = $connection->prepare($sql);

            $stmt->bindParam(':email',$email);
            $stmt->bindParam(':cpf_cnpj',$cpf_cnpj);
            $stmt->bindParam(':senha',$senha);
            $stmt->bindParam(':telefone',$telefone);
            $stmt->bindParam(':nome_cliente', $nome_cliente);
            
            $stmt->execute();
            echo '{"notice": {"text":"Cliente adicionado com sucesso."}';

        } catch (PDOException $e) {
            echo '{"error": {"text": ' .$e->getMessage().'}';
        }
    });

    $app->put('/cliente/update/{id}', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [POST] pessoas/. Received Data: " . json_encode($args));
        $body = $request->getParsedBody();
        $connection = $this->db;
        $id = $request->getAttribute('id');
        $email = $request->getParam('email');
        $cpf_cnpj = $request->getParam('cpf_cnpj');
        $senha= $request->getParam('senha');
        $telefone= $request->getParam('telefone');
        $nome_cliente= $request->getParam('nome_cliente');
        
        $sql = 


        "UPDATE cliente SET email = :email, cpf_cnpj = :cpf_cnpj, senha = :senha, telefone = :telefone, nome_cliente = :nome_cliente where cod_cliente = $id ;";
       try {
            
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':email',$email);
            $stmt->bindParam(':cpf_cnpj',$cpf_cnpj);
            $stmt->bindParam(':senha',$senha);
            $stmt->bindParam(':telefone',$telefone);
            $stmt->bindParam(':nome_cliente', $nome_cliente);
            
            $stmt->execute();
            echo '{"notice": {"text":"Cliente alterado com sucesso."}';

        } catch (PDOException $e) {
            echo '{"error": {"text": ' .$e->getMessage().'}';
        }
    });


    $app->delete('/cliente/delete/{id}', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [PUT] clientes/. Received Data: " . json_encode($args));
        $body = $request->getParsedBody();
        $connection = $this->db;
        $id = $request->getAttribute('id');
        
        
        $sql = "DELETE from cliente where cod_cliente = $id ;";
       try {
            
            $stmt = $connection->prepare($sql);
            
            $stmt->execute();
            echo '{"notice": {"text":"Cliente retirado do registro com sucesso."}';

        } catch (PDOException $e) {
            echo '{"error": {"text": ' .$e->getMessage().'}';
        }
    });

};