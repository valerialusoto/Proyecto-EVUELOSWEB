<?php
$serverName = "EMMANUEL";
$connectionInfo = array("Database" => "ServiciosWeb");

$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
    // Verifica si se han enviado datos de inicio de sesión
    if (isset($_POST['uname']) && isset($_POST['psw'])) {
        $username = $_POST['uname'];
        $password = $_POST['psw'];

        // Prevenir SQL injection utilizando consultas preparadas
        $sql = "SELECT u.*, r.Rol FROM usuarios u
                INNER JOIN Roles r ON u.idRol = r.idRol
                WHERE u.Correo = ? AND u.Contrasena = ?";
        $params = array($username, $password);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Verifica si se encontró un usuario con las credenciales proporcionadas
        if (sqlsrv_has_rows($stmt)) {
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            $userRol = $row['Rol'];

            // Verifica si el usuario tiene permisos de Administrador (rol 1)
            if ($userRol === 'Administrador') {
                // Redirige a HomeAdministrador.html después de cerrar la alerta
                echo '<script>window.location.href = "HomeAdministrador.html";</script>';
                exit(); // Asegura que el script se detenga después de la redirección
            } else {
                echo '<script>alert("El usuario no tiene permisos de Administrador."); window.location.href = "LoginAdministrador.html";</script>';
            }
        } else {
            echo '<script>alert("Credenciales incorrectas o el usuario no tiene permisos de Administrador."); window.location.href = "LoginAdministrador.html";</script>';
        }

        // Cierra la consulta
        sqlsrv_free_stmt($stmt);
    } else {
        echo '<script>alert("Faltan datos de inicio de sesión."); window.location.href = "LoginAdministrador.html";</script>';
    }

    // Cierra la conexión
    sqlsrv_close($conn);
} else {
    echo "Conexión fallida. Detalles del error: ";
    die(print_r(sqlsrv_errors(), true));
}
?>