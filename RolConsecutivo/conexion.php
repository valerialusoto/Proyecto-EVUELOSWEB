<?php
session_start();

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

            // Verifica si el usuario tiene permisos de Consecutivo (rol 3)
            if ($userRol === 'Consecutivo') {
                // Almacena el nombre y apellido del usuario en una variable de sesión
                $_SESSION['nombreUsuario'] = $row['Nombre']; // Reemplaza 'Nombre' con el campo real en tu base de datos
                $_SESSION['apellidoUsuario'] = $row['Apellido']; // Reemplaza 'Apellido' con el campo real en tu base de datos

                // Redirige a HomeConsecutivo.html después de cerrar la alerta
                echo '<script>window.location.href = "HomeConsecutivo.html";</script>';
                exit(); // Asegura que el script se detenga después de la redirección
            } else {
                echo '<script>alert("El usuario no tiene permisos de Consecutivo."); window.location.href = "LoginConsecutivo.html";</script>';
            }
        } else {
            echo '<script>alert("Credenciales incorrectas o el usuario no tiene permisos de Consecutivo."); window.location.href = "LoginConsecutivo.html";</script>';
        }

        // Cierra la consulta
        sqlsrv_free_stmt($stmt);
    } else {
        echo '<script>alert("Faltan datos de inicio de sesión."); window.location.href = "LoginConsecutivo.html";</script>';
    }

    // Cierra la conexión
    sqlsrv_close($conn);
} else {
    echo "Conexión fallida. Detalles del error: ";
    die(print_r(sqlsrv_errors(), true));
}
?>
