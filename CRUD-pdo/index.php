<?php
require_once ('classe-pessoa.php');
$p = new Pessoa("CRUDPDO", "localhost", "root", "..");
//conexão com o db através de PDO

if(isset($_GET['id_up']))//Verificando se a pessoa clicou no btn -ATUALIZAR-
{
    $id_up = addslashes($_GET['id_up']);
    $res = $p ->buscarDadosPessoa($id_up);
}

?>
<!DOCTYPE html>
<html>
<head lang="pt-br">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD COM PDO</title>

    <!-- STYLE CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <!--TOARST (para os alerts) -->
    <link href="js/toastr/build/toastr.min.css" rel="stylesheet"/>
   
</head>
<body>
    <section class="left">
        <form method="post">
            <h2>CADASTRAR PESSOA</h2>

            <label for="nome">Nome</label>
            <input type="text" name="nome"  maxlength="50" id="nome" value="<?php if(isset($res)){echo $res['nome'];}?>">

            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone"  maxlength="20" value= "<?php if(isset($res)){echo $res['telefone'];}?>">

            <label for="email">E-mail</label>
            <input type="text" name="email" id="email"  maxlength="50" value="<?php if(isset($res)){echo $res['email'];}?>">

            <input type="hidden" name="id_upd" value="<?php if(isset($res)){$id_upd = $res['id_user'];echo $id_upd;}else{$id_upd = NULL;}?>">

            <input type="submit" name="btn" value="<?php if(isset($res)){echo 'Atualizar';}else{echo 'Cadastrar';}?>">
        </form>
 <?php
    if(isset($_POST['nome']))
    {
        //------------------------------ ATUALIZAR -EMAIL ------------------------
        if($id_upd != null)
        {
        $verifica = $res['email'];
        //Pegando o email atual

        if($p->EmailAtualizado())
        {
            //guardando todos os emails do db em um array
             $Emailsdb = (array_column($resp, 'email'));
             if(in_array($verifica, $Emailsdb))
             {  
                //apagando o que deveria ser o email atual do array
                $var = array_search($verifica, $Emailsdb);
                unset($Emailsdb[$var]);
            }            
        }
        //------------------------------ VALIDAÇÕES ------------------------
           $p->SanitizarDados($_POST['id_upd'], $_POST['nome'], $_POST['telefone'], $_POST['email']);

            if(!empty($nome) && !empty($telefone) && !empty($email))
            {
                //conferindo se o email enviado está no array
                if(!in_array($email, $Emailsdb))
                {   
                    //ATUALIZAR DADOS
                    $p->AtualizarDados($id_upd,$nome,$telefone,$email);
                    header("location: index.php");
                    exit();
                }
                else
                {
                     $p->alert('error', 'Email já cadastrado no banco de dados');
                }
            }
            else
            {
                 $p->alert('warning', 'Preencha todos os campos corretamente');
            }
        }

        //----------------------------CADASTRAR-------------------------------
        else
        {
            $p->SanitizarDados($_POST['id_upd'], $_POST['nome'], $_POST['telefone'], $_POST['email']);

            if(!empty($nome) && !empty($telefone) && !empty($email))
            {  
                if(!$p->cadastrarPessoa($nome,$telefone, $email))
                  {
                      $p->alert('error', 'Atenção, email já cadastrado');
                  }
             }
            else
            {
                $p->alert('info', 'Preencha todos os campos corretamente');
            }    
        }
    }
       
    ?>
    </section>
    <section class="right">
             <table>
            <thead>
            <tr class="titulo">
                <th>Nome</th>
                <th>Telefone</th>
                <th colspan="4">Email</th>
            </tr>
            </thead>
        <tbody role='rowgroup'>
        <?php
           $dados = $p->buscarDados();
           if(count($dados)> 0)// SE TEM PESSOAS CADASTRADAS NO BANCO
           {
                for($i=0; $i < count($dados); $i++)
                {
                    echo "<tr role='row'>";
                    foreach ($dados[$i] as $k => $v)
                    {
                        if($k != "id_user")
                        {
                            echo"<td role='cell'>".$v."</td>";
                        }
                    }
                    echo"<td role='cell'>
                    <a href='index.php?id_up=".($dados [$i]['id_user'])."/editar/'>Editar</a>

                    <a href='index.php?id_user=".$dados [$i]['id_user']."'>Excluir</a>
                    </td> 
                    <tr>";
                }
           }
           else //SE NÃO TEM PESSOAS CADASTRADAS 
           {
             $p->alert('info', 'Ainda não há pessoas cadastradas!');
           }
        ?>
        </tbody>
        </table>
    </section>

    <!-- TOARST javascript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
     <script src="js/toastr/build/toastr.min.js"></script>

    <script>
        //configurações padrões (alert)
                toastr.options = {
                  "closeButton": true,
                  "debug": false,
                  "newestOnTop": false,
                  "progressBar": false,
                  "positionClass": "toast-top-full-width",
                  "preventDuplicates": false,
                  "onclick": null,
                  "showDuration": "300",
                  "hideDuration": "1000",
                  "timeOut": "5000",
                  "extendedTimeOut": "1000",
                  "showEasing": "swing",
                  "hideEasing": "linear",
                  "showMethod": "fadeIn",
                  "hideMethod": "fadeOut"
            }
    <?php
    if(isset($alert))
    {
        echo $alert;
        //Variável vinda da função alert
    }
    ?>
   </script>
</body>
</html>

<?php
if(isset($_GET['id_user']))
{
    $id_pessoa = addslashes($_GET['id_user']);
    $p->excluirPessoa($id_pessoa);
    header("location: index.php");
    exit();
}
?>
