<?php

$result = null;
$erro = null;
$valido = false;

    // validação isset, para saber essa variável validar foi definida
    // e se está disponível para você consultar o valor.
    if(isset($_REQUEST["validar"]) && $_REQUEST["validar"] == true )
    {
        // Verifica se o nome tem o tamanho menor que 5 caracteres.
        if(strlen(utf8_decode($_POST["nome"])) < 5 )
        {
            $erro = "Preencha o campo nome corretamente (5 ou mais caracteres)";
        }
        else if(strlen(utf8_decode($_POST["email"])) < 6)
        {
            $erro = "E-mail inválido, preencha corretamente";
        }
        else if($_POST["telefone"] == false)
        {
            $erro = "Telefone inválido, preencha corretamente";
        }
        else if($_POST["genero"] != "M" && $_POST["genero"] != "F")
        {
            $erro = "Selecione o campo genero corretamente";
        }
        
        // Só entrará nessa condição se todas as anteriores estiverem validadas com sucesso.
        else
        {
           $valido = true;
           
           try
           {
              $connection = new PDO("mysql:host=localhost;dbname=db", "root", "senhadobanco");  
              $connection->exec("set names utf8");
              
           }
           catch(PDOException $e)
           {
              echo "Falha: ". $e->getMessage();
              exit();
           }
           
           $sql = "INSERT INTO aluno
                   (nome, email, telefone, genero, data_de_nascimento)
                   VALUES(:nome, :email, :telefone, :genero, :data_de_nascimento)";
           
           // ele prepara a query acima para ser executado posteriormente
           $stmt = $connection->prepare($sql);
           
           // o metodo bindParam associa um valor ao ?
           $stmt->bindParam (':nome', $_POST["nome"]);
           $stmt->bindParam (':email', $_POST["email"]);
           $stmt->bindParam (':telefone', $_POST["telefone"]);
           $stmt->bindParam (':genero', $_POST["genero"]);
           $stmt->bindParam (':data_de_nascimento', $_POST["data"]);
           
           $stmt->execute();
        
           if($stmt->errorCode() != "00000")
           {
              $valido = false;
              $erro = "Erro código " . $stmt->errorCode() . ": ";
              $erro .= implode(", ", $stmt->errorInfo());
           }
           
        }
    }
    
?>
<HTML>
    <HEAD>
        <TITLE>Cadastro</TITLE>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" src="jquery.js"></script>
        <script type="text/javascript" src="jquery.maskedinput.js"></script>
    </HEAD>
        <script type="text/javascript">
          $(document).ready(function()
          {
             $("input.telefone").mask("(99)9999-9999");
             $("input.data").mask("99/99/9999");
          });
        </script>
    <BODY>
        <?php
        
            // Se dados validados com sucesso aparece a mensagem ao usuário.
            if($valido == true)
            {
                echo "Cadastro realizado com sucesso!"; 
            }
            // se não for validado, irá aparecer a mensagem de erro e todo o formulário.
            else
            {
                
            // verifica se tem erro
            if(isset($erro))
            {
                echo $erro . "<BR><BR>";
            }
        
        ?>
        <FORM method=POST action="cadastro.php?validar=true">
        <fieldset>
            <legend>Cadastro de aluno:</legend>
            Nome:
            <INPUT type=TEXT name=nome
            <?php if(isset($_POST["nome"])) { echo "value='" . $_POST["nome"] . "'"; } ?>
            ><BR><BR>
            
            E-mail:
            <INPUT type=TEXT name=email
            <?php if(isset($_POST["email"])) { echo "value='" . $_POST["email"] . "'"; } ?>
            ><BR><BR>
            
            Telefone:
            <INPUT type=TEXT name=telefone class=telefone
            <?php if(isset($_POST["telefone"])) { echo "value='" . $_POST["telefone"] . "'";}
            ?>
            ><BR><BR>
            
            Gênero:
            <INPUT type=RADIO name=genero value="M"
            <?php if(isset($_POST["genero"]) && $_POST["genero"] == "M") { echo "checked"; } ?>
            >Masculino
            <INPUT type=RADIO name=genero value="F"
            <?php if(isset($_POST["genero"]) && $_POST["genero"] == "F") { echo "checked"; } ?>
            >Feminino
            <BR><BR>
            
            Data de Nascimento:
            <INPUT type=TEXT name=data class=data
            <?php if(isset($_POST["data"])) { echo "value='" . $_POST["data"] . "'";}
            ?>
            ><BR><BR>
            
            <INPUT type=SUBMIT value="Enviar">
        </fieldset>        
        </FORM>
        <?php
            }
        ?>        
    </BODY>
</HTML>