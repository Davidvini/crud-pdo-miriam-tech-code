<?php
// -------------------- CONEXAO ---------------------

try {
	$pdo = new PDO("mysql:dbname=CRUDPDO;host=localhost","root", "..");

} 
catch (PDOException $e) {
	echo "Erro com banco de dados: ".$e->getMessage();
}
catch (Exception $e){
	echo "Erro com banco de dados: ".$e->getMessage();
}

//db name
//host = endereço do servidor
//usuario e senha

// -------------------- INSERT ---------------------

//$res = $pdo->prepare("INSERT INTO usuarios(nome,telefone,email) VALUES (:n, :t, :e)");

//$res->bindValue(":n", "teste");
//$res->bindValue(":t", "00000001");
//$res->bindValue(":e", "teste@gmail.com");
//$res->execute();

//$pdo -> query("INSERT INTO usuarios(nome, telefone, email) VALUES ('hahahahahaha','000001010','teste2@gmail.com')");

// -------------------- DELETE E UPDATE ---------------------
//$res = $pdo->prepare("DELETE FROM usuarios WHERE id_user= :id");
//$id = 2;
//$res -> bindValue(":id", $id);
//$res ->execute();


//$res = $pdo->query("DELETE FROM usuarios WHERE id_user= '3'");

//$res = $pdo->prepare("UPDATE usuarios SET email = :e WHERE id_user = :id");
//$res->bindValue(":e", "testeghj@gmail.com");
//$res->bindValue(":id", 1);
//$res-> execute();

//$res =$pdo->query("UPDATE usuarios SET nome = 'David Vinicius' WHERE id_user= '1' ");

//$cmd = $pdo->prepare("SELECT * FROM usuarios WHERE id_user = :id");
//$cmd -> bindValue(":id", 1);
//$cmd->execute();
//$resultado = $cmd->fetch(PDO::FETCH_ASSOC);
//ou 
//echo "<pre>";
//$cmd->fetchAll
//print_r($resultado);
//echo "</pre>";

//foreach ( $resultado as $key => $value) {
//	echo $key.": ".$value."<br>";
//}

?>