
namespace imunisasi\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PDO;

class UserController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function listUsers(Request $request, Response $response)
    {
        // Logika untuk mendapatkan daftar pengguna dari database
        // ...
        // $users = $this->db->query('SELECT * FROM users')->fetchAll(PDO::FETCH_ASSOC);

        // Return response
        return $response->withJson($users);
    }

    public function viewUser(Request $request, Response $response, $args)
    {
        $userId = $args['id'];

        // Logika untuk mendapatkan pengguna berdasarkan ID dari database
        // ...
        // $user = $this->db->query('SELECT * FROM users WHERE id = ?')->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // Handle jika pengguna tidak ditemukan
            // ...
        }

        // Return response
        return $response->withJson($user);
    }

    // Metode lain untuk menangani operasi CRUD pengguna
    // ...
}