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

        $query = $db->query('CALL selectPasien()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/vaksin', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL selectVaksin()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/lokasi', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL selectLokasi()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/vaksinasi', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL selectVaksinasi()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get by id
    $app->get('/pasien/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('CALL selectPasienById(:id_pasien)');
        $query->bindParam(':id_pasien', $args['id'], PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/vaksin/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('CALL selectVaksinById(:id_vaksin)');
        $query->bindParam(':id_vaksin', $args['id'], PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/lokasi/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('CALL selectLokasiById(:id_lokasi)');
        $query->bindParam(':id_lokasi', $args['id'], PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/vaksinasi/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('CALL selectVaksinasiById(:id_vaksinasi)');
        $query->bindParam(':id_vaksinasi', $args['id'], PDO::PARAM_INT);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    // post data
    $app->post('/lokasi', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $name = $parsedBody["name"];
        $address = $parsedBody["address"];
        $city = $parsedBody["city"];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL insertLokasi(?, ?, ?)');
            $query->execute([$name, $address, $city]);
    
            $responseData = [
                'message' => 'Lokasi disimpan.'
            ];
    
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $responseData = [
                'error' => 'Terjadi kesalahan dalam penyimpanan lokasi.'
            ];
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    $app->post('/pasien', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $name = $parsedBody["name"];
        $birth = $parsedBody["birth"];
        $phone = $parsedBody["phone"];
        $address = $parsedBody["address"];
        $city = $parsedBody["city"];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL insertPasien(?, ?, ?, ?, ?)');
            $query->execute([$name, $birth, $phone, $address, $city]);
    
            $responseData = [
                'message' => 'Data pasien disimpan.'
            ];
    
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $responseData = [
                'error' => 'Terjadi kesalahan dalam penyimpanan data pasien.'
            ];
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    $app->post('/vaksin', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $name = $parsedBody["name"];
        $category = $parsedBody["category"];
        $stock = $parsedBody["stock"];

        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL insertVaksin(?, ?, ?)');
            $query->execute([$name, $category, $stock]);
    
            $responseData = [
                'message' => 'Data vaksin tersimpan.'
            ];
    
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $responseData = [
                'error' => 'Terjadi kesalahan dalam penyimpanan data vaksin.'
            ];
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    $app->post('/vaksinasi', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $p_id = $parsedBody["p_id"];
        $v_id = $parsedBody["v_id"];
        $l_id = $parsedBody["l_id"];

        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL insertVaksinasi(?, ?, ?)');
            $query->execute([$p_id, $v_id, $l_id]);
    
            $responseData = [
                'message' => 'Data vaksinasi tersimpan.'
            ];
    
            $response->getBody()->write(json_encode($responseData));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $responseData = [
                'error' => 'Terjadi kesalahan dalam penyimpanan data vaksinasi.'
            ];
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    });

    // put data
    $app->put('/vaksin/{id}', function (Request $request, Response $response, $args) {
        $vaksinId = $args['id'];
        $data = $request->getParsedBody();
    
        $name = $data['name'];
        $category = $data['category'];
        $stock = $data['stock'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL updateVaksinById(?, ?, ?, ?)');
            $query->execute([$vaksinId, $name, $category, $stock]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode([
                    'message' => 'Data tidak ditemukan'
                ]));
            } else {
                $response->getBody()->write(json_encode([
                    'message' => 'Data vaksin dengan ID ' . $vaksinId . ' telah diperbarui.'
                ]));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode([
                'message' => 'Database error ' . $e->getMessage()
            ]));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->put('/pasien/{id}', function (Request $request, Response $response, $args) {
        $pasienId = $args['id'];
        $data = $request->getParsedBody(); 
    
        $name = $data['name'];
        $birth = $data['birth'];
        $phone = $data['phone'];
        $address = $data['address'];
        $city = $data['city'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL updatePasienById(?, ?, ?, ?, ?, ?)');
            $query->execute([$pasienId, $name, $birth, $phone, $address, $city]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode([
                    'message' => 'Data tidak ditemukan'
                ]));
            } else {
                $response->getBody()->write(json_encode([
                    'message' => 'Data pasien dengan ID ' . $pasienId . ' telah diperbarui.'
                ]));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode([
                'message' => 'Database error ' . $e->getMessage()
            ]));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->put('/lokasi/{id}', function (Request $request, Response $response, $args) {
        $lokasiId = $args['id'];
        $data = $request->getParsedBody(); 

        $name = $data['name'];
        $address = $data['address'];
        $city = $data['city'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL updateLokasiById(?, ?, ?, ?)');
            $query->execute([$lokasiId, $name, $address, $city]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode([
                    'message' => 'Data tidak ditemukan'
                ]));
            } else {
                $response->getBody()->write(json_encode([
                    'message' => 'Data lokasi dengan ID ' . $lokasiId . ' telah diperbarui.'
                ]));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode([
                'message' => 'Database error ' . $e->getMessage()
            ]));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->put('/vaksinasi/{id}', function (Request $request, Response $response, $args) {
        $vaksinasiId = $args['id'];
        $data = $request->getParsedBody(); 

        $p_id = $data['p_id'];
        $v_id = $data['v_id'];
        $l_id= $data['l_id'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL updateVaksinasiById(?, ?, ?, ?)');
            $query->execute([$vaksinasiId, $p_id, $v_id, $l_id]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode([
                    'message' => 'Data tidak ditemukan'
                ]));
            } else {
                $response->getBody()->write(json_encode([
                    'message' => 'Data lokasi dengan ID ' . $vaksinasiId . ' telah diperbarui.'
                ]));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode([
                'message' => 'Database error ' . $e->getMessage()
            ]));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    
    // delete data
    $app->delete('/lokasi', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL deleteLokasi()');
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Seluruh data lokasi telah dihapus, dan nilai auto increment telah direset.'
                ]
            ));
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

    $app->delete('/vaksin', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL deleteVaksin()');
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Seluruh data vaksin telah dihapus, dan nilai auto increment telah direset.'
                ]
            ));
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

    $app->delete('/pasien', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL deletePasien()');
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Seluruh data pasien telah dihapus, dan nilai auto increment telah direset.'
                ]
            ));
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

    $app->delete('/vaksinasi', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL deleteVaksinasi()');
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Seluruh data vaksinasi telah dihapus, dan nilai auto increment telah direset.'
                ]
            ));
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

    //delete data by id
    $app->delete('/lokasi/{id}', function (Request $request, Response $response, $args) {
        $loc_id = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL DeleteLokasiById(?)');
            $query->execute([$loc_id]);
    
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
                        'message' => 'Data lokasi dengan ID ' . $loc_id . ' telah dihapus.'
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

    $app->delete('/vaksinasi/{id}', function (Request $request, Response $response, $args) {
        $loc_id = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL DeleteVaksinasiById(?)');
            $query->execute([$loc_id]);
    
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
                        'message' => 'Data vaksinasi dengan ID ' . $loc_id . ' telah dihapus.'
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

    $app->delete('/pasien/{id}', function (Request $request, Response $response, $args) {
        $loc_id = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL DeletePasienById(?)');
            $query->execute([$loc_id]);
    
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
                        'message' => 'Data pasien dengan ID ' . $loc_id . ' telah dihapus.'
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

    $app->delete('/vaksin/{id}', function (Request $request, Response $response, $args) {
        $loc_id = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL DeleteVaksinById(?)');
            $query->execute([$loc_id]);
    
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
                        'message' => 'Data vaksin dengan ID ' . $loc_id . ' telah dihapus.'
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
