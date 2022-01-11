<?php

class Pessoa{
	//6 funcoes
	//CONEXAO COM O BANCO DE DADOS
	private $pdo;

	public function __construct($dbname, $host, $user, $senha)
	{
		try {
			$this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host,$user, $senha);

		} catch (PDOException $e) {
			echo "Erro com banco de dados: ".$e->getMessage();
			exit();
		}
		catch (Exception $e) {
			echo "Erro genérico: ".$e->getMessage();
			exit();
		}
	}
//FUNCAO PARA BUSCAR OS DADOS E COLOCAR NA TABLE
	public function buscarDados()
	{
		$res=array();
		$cmd = $this->pdo->query("SELECT * FROM usuarios ORDER BY nome");
		$res = $cmd->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function CadastrarPessoa($nome,$telefone, $email){
		
		//ANTES DE CADASTRATR VERIFICAR SE O EMAIL JÁ ESTA CADASTRADO
		$cmd = $this -> pdo->prepare("SELECT id_user FROM usuarios WHERE email = :e");
		$cmd->bindValue(":e", $email);
		$cmd->execute();

		if($cmd-> rowCount()>0){

			return false;

		}else{
			$cmd = $this->pdo->prepare("INSERT INTO usuarios(email,telefone,nome) VALUES (:e,:t,:n)");
			$cmd ->bindValue(":n", $nome);
			$cmd ->bindValue(":e", $email);
			$cmd ->bindValue(":t", $telefone);
			$cmd-> execute();
			return true;
		}
	}
	
	public function excluirPessoa($id){
		$cmd = $this->pdo->prepare("DELETE FROM usuarios WHERE id_user = :id");
		$cmd->bindValue(":id", $id);
		$cmd ->execute();
	}

	//BUSCAR OS DADOS DE UMA PESSOA ESPECIFICA
	public function buscarDadosPessoa($id){
		$res = array();
		$cmd = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_user = :id");
		$cmd -> bindValue(":id", $id);
		$cmd->execute();
		$res = $cmd->fetch(PDO::FETCH_ASSOC);
		return $res;
	}

	// ATUALIZAR OU EDITAR 

	public function atualizarDados($id, $nome, $telefone, $email)
	{
		$cmd = $this->pdo->prepare("UPDATE usuarios SET nome = :n, telefone =:t, email = :e WHERE id_user = :id");
		$cmd ->bindValue(":id", $id);
		$cmd ->bindValue(":n", $nome);
		$cmd ->bindValue(":t", $telefone);
		$cmd ->bindValue(":e", $email);
		$cmd ->execute();		
	}

	public function EmailAtualizado()
	{
		global $resp;
		$resp=array();
		$cmd = $this->pdo->query("SELECT email FROM usuarios");
		$resp = $cmd->fetchAll(PDO::FETCH_ASSOC);
		return $resp;
		//Utilizada para o tratamento de erro do EMAIL durante a atualização dos dados 
	}

	public function SanitizarDados($id, $nome, $telefone, $email)
	{
		global $email;
		global $nome;
		global $id_upd;
		global $telefone;

		//sanitizando todas as variáveis e retorndo elas
		 $regNome =  "/[^\w\d\s:\p{L}]/u";
         $regEmail = "/^\S+@\S+\.\S+$/";

         //SANITIZANDO VARIAVEIS COM REGEX
            if(preg_match($regEmail,$_POST['email']))
            {
                $email = $_POST['email'];     
            }
            else
            {
                $email = "";
            }

            if(!preg_match($regNome,$_POST['nome']))
            {
                $nome = $_POST['nome'];
            }
            else
            {
                $nome = "";
            }

            if(is_numeric($_POST['id_upd']) or is_null($_POST['id_upd']))
            {
                $id_upd = $_POST['id_upd']; 
            }
            else
            {
                $id_upd = "";
            }

            if(is_numeric($_POST['telefone']))
            {
            	 $telefone = $_POST['telefone'];
            }
            else
            {
                $telefone = "";
            }

            return $email;
            return $nome;
            return $telefone;
           	return $id_upd; 

           	//Se algum problema ocorrer durante a sanitização, a variável é criada, mas com um valor vazio, caindo no if(!empty) e retornando um alert de ERRO.
           
	}
	//ALERTAS DE ERRO JS
	public function alert($tipo, $mensagem)
	{
		global $alert;
		 $alert = $alert."toastr.".$tipo."('".$mensagem."');";
		 //Ao ser chamada a função exibe um alert a partir dos parametros $tipo e $mensagem
		 
		 //Lista de tipos 

		 //* info
		 //* warning
		 //* error
		 //* sucess
	}
} 

?>