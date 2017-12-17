<!DOCTYPE html>
<html lang="es">
<head>
	<title>IurApp Print</title>
	<meta charset="utf-8">
</head>
<body>
<?php
if(!empty($_POST)) {
$data = 
"DB_HOST=".trim($_POST['DB_HOST'])."
DB_PORT=".trim($_POST['DB_PORT'])."
DB_USER=".trim($_POST['DB_USER'])."
DB_PASS=".trim($_POST['DB_PASS'])."
DB_NAME=iurapp";
$env = @file_put_contents(".env", $data);
echo "<script>location.href = './';</script>";
}
?>
<?php
	require 'config.inc.php';
	global $config;
	$cn = new MySqliClass();
	$db = $cn->getConnection();
	$v2 = false;
	if ($db->connect_error) {
		$error = "".$db->connect_error;
		// var_dump(strpos($error, "Unknown database"));die();
		if ( strpos($error, "Unknown database") !== false ) { // la base de datos no existe
			$install = $cn->connectServer();
			$sql = file_get_contents("bk/install.sql");
			@list($sql_db, $sql_table) = @explode("##", $sql);
			$v1 = $install->query($sql_db);
			if ( $v1 === true ) {
				$db = $cn->connect();
				$v2 = $db->query($sql_table);
				if ( $v2 === true ) {
					echo "Instalación exitosa :)";
					die();
				} else {
					echo "Ocurrió un error al crear la tabla!<br> Revise la configuración del servidor de impresión. <br> Configure de acuerdo a su servidor: <br>";
				}
			} else {
				echo "Ocurrió un error!<br> Revise la configuración del servidor de impresión. <br> Configure de acuerdo a su servidor: <br>";
			}
			$install->close();
			$db->close();
		} else {
			echo "Ocurrió un error!<br> Revise la configuración del servidor de impresión. <br> Configure de acuerdo a su servidor: <br>";
		}
	} else {
		$v2 = true;
		echo "Base de datos Conectada :)";
	}
if ( !$v2 ) { ?>

<form action="./" method="POST">
	<label>DB_HOST <span style="color: red;">(*)</span>: </label><input type="text" name="DB_HOST" required="required" placeholder="DB_HOST" value="<?php echo $config['accessBD']['DB_HOST'] ?>"><br>
	<label>DB_PORT <span style="color: red;">(*)</span>: </label><input type="text" name="DB_PORT" required="required" placeholder="DB_PORT" value="<?php echo $config['accessBD']['DB_PORT'] ?>"><br>
	<label>DB_USER <span style="color: red;">(*)</span>: </label><input type="text" name="DB_USER" required="required" placeholder="DB_USER" value="<?php echo $config['accessBD']['DB_USER'] ?>"><br>
	<label>DB_PASS <span style="color: red;"></span>: </label><input type="text" name="DB_PASS" placeholder="DB_PASS" value="<?php echo $config['accessBD']['DB_PASS'] ?>"><br>
	<label>DB_NAME <span style="color: red;">(*)</span>: </label><input type="text" required="required" placeholder="DB_NAME" value="<?php echo empty($config['accessBD']['DB_NAME'])? 'iurapp' : $config['accessBD']['DB_NAME']; ?>" disabled ><br>
	<input type="submit" value="Guardar" >
</form>
<?php
}
?>

</body>
</html>