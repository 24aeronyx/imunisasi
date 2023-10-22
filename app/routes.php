<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    // get
    $app->get('/pasien', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('SELECT * FROM pasien');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get by id
    $app->get('/pasien/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM pasien WHERE id=?');
        $query->execute([$args['id']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    // post data
    $app->post('/pasien', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();

        $id = $parsedBody["id"]; // menambah dengan kolom baru
        $countryName = $parsedBody["name"];

        $db = $this->get(PDO::class);

        $query = $db->prepare('INSERT INTO pasien (id, name) values (?, ?)');

        // urutan harus sesuai dengan values
        $query->execute([$id, $countryName]);

        $lastId = $db->lastInsertId();

        $response->getBody()->write(json_encode(
            [
                'message' => 'pasien disimpan dengan id ' . $lastId
            ]
        ));

        return $response->withHeader("Content-Type", "application/json");
    });

    // put data
    $app->put('/pasien/{id}', function (Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();

        $currentId = $args['id'];
        $countryName = $parsedBody["name"];
        $db = $this->get(PDO::class);

        $query = $db->prepare('UPDATE pasien SET name = ? WHERE id = ?');
        $query->execute([$countryName, $currentId]);

        $response->getBody()->write(json_encode(
            [
                'message' => 'pasien dengan id ' . $currentId . ' telah diupdate dengan nama ' . $countryName
            ]
        ));

        return $response->withHeader("Content-Type", "application/json");
    });

    // delete data
    $app->delete('/pasien/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);

        try {
            $query = $db->prepare('DELETE FROM pasien WHERE id = ?');
            $query->execute([$currentId]);

            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'Data tidak ditemukan'
                    ]
                ));
            } else {
                $response->getBody()->write(json_encode(
                    [
                        'message' => 'pasien dengan id ' . $currentId . ' dihapus dari database'
                    ]
                ));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Database error ' . $e->getMessage()
                ]
            ));
        }

        return $response->withHeader("Content-Type", "application/json");
    });
};
