<?php
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
return function ($app, $container) {
    // GET All individual entities from DB
    $app->get('/pessoas/', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [GET] pessoas/");
        $connection = $this->db;
        $stmt = $connection->query("SELECT * FROM Users;");
        $data = $stmt->fetchAll();
        return $response->withJson($data)
            ->withStatus(200);
    });
    // POST a new user to DB;
    // REMEMBER: 2 Steps
    $app->post('/pessoas/[id]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Pessoas App: [POST] pessoas/. Received Data: " . json_encode($args));
        $body = $request->getParsedBody();
        $connection = $this->db;
        $data = null;
        if (isset($args['id'])) {
            $stmt = $connection->prepare('INSERT INTO Users (user_login, user_password, user_salt) VALUES (?, ?, ?);');
            $stmt->execute($body["user_login"], $body["user_password"], $body["user_salt"]);
        } else {
            $stmt = $connection->query('SELECT AUTO_INCREMENT id
                                            FROM information_schema.TABLES 
                                           WHERE TABLE_SCHEMA=\'AulaPW\' AND 
                                                 TABLE_NAME=\'Users\';');
            $data = $stmt->fetchAll();
        }
        return $response->withJson($data)
            ->withStatus(200);
    });
};