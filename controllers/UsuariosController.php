<?php

require_once __DIR__ . '/../config/database.php';

class UsuariosController {

    private $db;

    public function __construct() {

        $database = new Database();
        $this->db = $database->getConnection();
    }

    // =====================================================
    // LISTAR USUARIOS
    // =====================================================

    public function listarUsuarios() {

        try {

            $sql = "

                SELECT

                    u.id_usuario,
                    u.nombre_completo,
                    u.ci,
                    u.celular,
                    u.correo,
                    u.estado_active,

                    r.nombre_rol,

                    g.nombre_genero

                FROM usuarios u

                INNER JOIN roles r
                    ON u.id_rol = r.id_rol

                INNER JOIN generos g
                    ON u.id_genero = g.id_genero

                ORDER BY u.id_usuario DESC

            ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e){

            return [];
        }
    }

    // =====================================================
    // LISTAR ROLES
    // =====================================================

    public function listarRoles() {

        $stmt = $this->db->prepare("
            SELECT *
            FROM roles
            ORDER BY nombre_rol
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =====================================================
    // LISTAR GENEROS
    // =====================================================

    public function listarGeneros() {

        $stmt = $this->db->prepare("
            SELECT *
            FROM generos
            ORDER BY nombre_genero
        ");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =====================================================
    // REGISTRAR USUARIO
    // =====================================================

    public function registrarUsuario(

        $idRol,
        $idGenero,
        $nombre,
        $ci,
        $celular,
        $correo,
        $password

    ) {

        try {

            $this->db->beginTransaction();

            $passwordHash =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

            $sql = "

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
                    :rol,
                    :genero,
                    :nombre,
                    :ci,
                    :celular,
                    :correo,
                    :pass
                )

            ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([

                ':rol'      => $idRol,
                ':genero'   => $idGenero,
                ':nombre'   => $nombre,
                ':ci'       => $ci,
                ':celular'  => $celular,
                ':correo'   => $correo,
                ':pass'     => $passwordHash

            ]);

            $idUsuario =
                $this->db->lastInsertId();

            // ==========================================
            // SI ES BARBERO
            // ==========================================

            if($idRol == 3){

                $sqlBarbero = "

                    INSERT INTO barberos
                    (
                        id_barbero,
                        porcentaje_comision
                    )
                    VALUES
                    (
                        :id,
                        50
                    )

                ";

                $stmtBarbero =
                    $this->db->prepare($sqlBarbero);

                $stmtBarbero->execute([
                    ':id' => $idUsuario
                ]);
            }

            // ==========================================
            // SI ES CLIENTE
            // ==========================================

            if($idRol == 4){

                $sqlCliente = "

                    INSERT INTO clientes
                    (
                        id_genero,
                        nombre_completo,
                        celular,
                        correo
                    )
                    VALUES
                    (
                        :genero,
                        :nombre,
                        :celular,
                        :correo
                    )

                ";

                $stmtCliente =
                    $this->db->prepare($sqlCliente);

                $stmtCliente->execute([

                    ':genero'  => $idGenero,
                    ':nombre'  => $nombre,
                    ':celular' => $celular,
                    ':correo'  => $correo

                ]);
            }

            $this->db->commit();

            return true;

        } catch(PDOException $e){

            if($this->db->inTransaction()){

                $this->db->rollBack();
            }

            return false;
        }
    }

    // =====================================================
    // ACTIVAR / DESACTIVAR
    // =====================================================

    public function cambiarEstado(
        $idUsuario,
        $estado
    ){

        try {

            $sql = "

                UPDATE usuarios

                SET estado_active = :estado

                WHERE id_usuario = :id

            ";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([

                ':estado' => $estado,
                ':id'     => $idUsuario

            ]);

        } catch(PDOException $e){

            return false;
        }
    }
}

/* =====================================================
   RECEPTOR POST
===================================================== */

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $controller = new UsuariosController();

    // REGISTRAR

    if(
        isset($_POST['action'])
        &&
        $_POST['action'] === 'registrar_usuario'
    ){

        $resultado =
            $controller->registrarUsuario(

                intval($_POST['id_rol']),
                intval($_POST['id_genero']),
                trim($_POST['nombre_completo']),
                trim($_POST['ci']),
                trim($_POST['celular']),
                trim($_POST['correo']),
                trim($_POST['password'])

            );

        if($resultado){

            header(
                "Location: ../views/usuarios.php?status=success"
            );

        } else {

            header(
                "Location: ../views/usuarios.php?status=error"
            );
        }

        exit();
    }

    // CAMBIAR ESTADO

    if(
        isset($_POST['action'])
        &&
        $_POST['action'] === 'cambiar_estado'
    ){

        $controller->cambiarEstado(

            intval($_POST['id_usuario']),

            intval($_POST['estado'])

        );

        header(
            "Location: ../views/usuarios.php"
        );

        exit();
    }
}