<?php
// controllers/AuthController.php

// =====================================================
// INICIAR SESIÓN
// =====================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================
// CONEXIÓN DB
// =====================================================

require_once __DIR__ . '/../config/database.php';

class AuthController {

    // =====================================================
    // LOGIN
    // =====================================================

    public function login($correo, $password) {

        $database = new Database();

        $db = $database->getConnection();

        if ($db === null) {

            return false;
        }

        try {

            $query = "
                SELECT 
                    u.id_usuario,
                    u.id_rol,
                    u.nombre_completo,
                    u.password_hash,
                    r.nombre_rol

                FROM usuarios u

                INNER JOIN roles r
                ON u.id_rol = r.id_rol

                WHERE u.correo = :correo
                AND u.estado_active = 1

                LIMIT 1
            ";

            $stmt = $db->prepare($query);

            $stmt->bindParam(':correo', $correo);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // PASSWORD ENCRIPTADO O TEXTO PLANO
                if (
                    password_verify($password, $row['password_hash'])
                    ||
                    $password === $row['password_hash']
                ) {

                    // ==========================================
                    // SESIONES
                    // ==========================================

                    $_SESSION['logged_in'] = true;

                    $_SESSION['id_usuario'] = $row['id_usuario'];

                    $_SESSION['id_rol'] = $row['id_rol'];

                    $_SESSION['nombre'] = $row['nombre_completo'];

                    $_SESSION['rol'] = $row['nombre_rol'];

                    $_SESSION['correo'] = $correo;

                    return true;
                }
            }

            return false;

        } catch (PDOException $e) {

            return false;
        }
    }

    // =====================================================
    // REGISTRO CLIENTE
    // =====================================================

    public function registrarCliente(

        $nombre,
        $correo,
        $password,
        $celular,
        $id_genero

    ) {

        $database = new Database();

        $db = $database->getConnection();

        try {

            // ==========================================
            // VALIDAR CORREO REPETIDO
            // ==========================================

            $queryValidar = "
                SELECT id_usuario
                FROM usuarios
                WHERE correo = :correo
                LIMIT 1
            ";

            $stmtValidar = $db->prepare($queryValidar);

            $stmtValidar->execute([
                ':correo' => $correo
            ]);

            if ($stmtValidar->rowCount() > 0) {

                return "correo_existe";
            }

            // ==========================================
            // ENCRIPTAR PASSWORD
            // ==========================================

            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // ==========================================
            // TRANSACCIÓN
            // ==========================================

            $db->beginTransaction();

            // ==========================================
            // INSERT USUARIO
            // ROL 4 = CLIENTE
            // ==========================================

            $queryUsuario = "
                INSERT INTO usuarios
                (
                    id_rol,
                    id_genero,
                    nombre_completo,
                    ci,
                    celular,
                    correo,
                    password_hash
                )

                VALUES
                (
                    4,
                    :id_genero,
                    :nombre,
                    :ci,
                    :celular,
                    :correo,
                    :password
                )
            ";

            $stmtUsuario = $db->prepare($queryUsuario);

            // CI temporal automático
            $ciTemporal = 'CLI-' . rand(1000,9999);

            $stmtUsuario->execute([

                ':id_genero' => $id_genero,

                ':nombre' => $nombre,

                ':ci' => $ciTemporal,

                ':celular' => $celular,

                ':correo' => $correo,

                ':password' => $passwordHash
            ]);

            // ==========================================
            // INSERT CLIENTE
            // ==========================================

            $queryCliente = "
                INSERT INTO clientes
                (
                    id_genero,
                    nombre_completo,
                    celular,
                    correo
                )

                VALUES
                (
                    :id_genero,
                    :nombre,
                    :celular,
                    :correo
                )
            ";

            $stmtCliente = $db->prepare($queryCliente);

            $stmtCliente->execute([

                ':id_genero' => $id_genero,

                ':nombre' => $nombre,

                ':celular' => $celular,

                ':correo' => $correo
            ]);

            // ==========================================
            // COMMIT
            // ==========================================

            $db->commit();

            return "success";

        } catch (PDOException $e) {

            if ($db->inTransaction()) {

                $db->rollBack();
            }

            return "error";
        }
    }

    // =====================================================
    // LOGOUT
    // =====================================================

    public function logout() {

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: ../views/login.php");

        exit();
    }
}

// =====================================================
// INSTANCIA
// =====================================================

$auth = new AuthController();

// =====================================================
// LOGIN
// =====================================================

if (

    $_SERVER['REQUEST_METHOD'] === 'POST'

    &&

    isset($_POST['action'])

    &&

    $_POST['action'] === 'login'

) {

    $correo = trim($_POST['correo']);

    $password = trim($_POST['password']);

    if ($auth->login($correo, $password)) {

        // ==========================================
        // REDIRECCIÓN POR ROL
        // ==========================================

        if ($_SESSION['id_rol'] == 1) {

            header("Location: ../views/dashboard.php");

        } elseif ($_SESSION['id_rol'] == 2) {

            header("Location: ../views/caja.php");

        } elseif ($_SESSION['id_rol'] == 3) {

            header("Location: ../views/agenda.php");

        } elseif ($_SESSION['id_rol'] == 4) {

            header("Location: ../views/panel_cliente.php");

        } else {

            header("Location: ../views/dashboard.php");
        }

    } else {

        header("Location: ../views/login.php?error=1");
    }

    exit();
}

// =====================================================
// REGISTRO CLIENTE
// =====================================================

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    &&
    isset($_POST['action'])
    &&
    $_POST['action'] === 'registro_cliente'
) {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);
    $celular = trim($_POST['celular']);
    $id_genero = intval($_POST['id_genero']);
    $resultado = $auth->registrarCliente(
        $nombre,
        $correo,
        $password,
        $celular,
        $id_genero

    );
    if ($resultado === 'success') {
        header("Location: ../views/login.php?registro=success");
    } elseif ($resultado === 'correo_existe') {
        header("Location: ../views/registro.php?error=correo");
    } else {
        header("Location: ../views/registro.php?error=general");
    }
    exit();
}
// =====================================================
// LOGOUT
// =====================================================
if (
    $_SERVER['REQUEST_METHOD'] === 'GET'
    &&
    isset($_GET['action'])
    &&
    $_GET['action'] === 'logout'
) {
    $auth->logout();
}