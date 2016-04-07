<?php

$result = null;
$erro = null;
$valido = false;
    
?>
<HTML>
    <HEAD>
        <TITLE>Listar</TITLE>
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
        
        <?php
            }
        ?>
        
         <FORM method=POST action="listar.php?validar=true">
        <fieldset>
            <legend>Lista de alunos:</legend>
            
            Gênero:
            <INPUT type=RADIO name=genero value="M"
            <?php if($_POST["genero"] == "M") { echo "checked"; } ?>
            >Masculino
            <INPUT type=RADIO name=genero value="F"
            <?php if($_POST["genero"] == "F") { echo "checked"; } ?>
            >Feminino            
            <BR><BR>
            
            <INPUT type=SUBMIT value="Buscar">
            <BR><BR>
            
        <?php    
        
         if(isset($_REQUEST["validar"]) && $_REQUEST["validar"] == true )
    	{
       
                  
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
           
           $sql = "SELECT * FROM aluno WHERE genero =:genero ORDER BY nome ASC";
           
           // ele prepara a query acima para ser executado posteriormente
           $stmt = $connection->prepare($sql);
           
           $stmt->bindValue(':genero', $_POST["genero"]);
           
           $stmt->execute();
           
           
           $aluno = $stmt->fetchAll(PDO::FETCH_ASSOC);
           
           
           foreach ($aluno as $a) {
  		echo  "- {$a['nome']} - {$a['email']} - {$a['telefone']} - {$a['data_de_nascimento']}<br>";
  		}
        
           if($stmt->errorCode() != "00000")
           {
              $valido = false;
              $erro = "Erro código " . $stmt->errorCode() . ": ";
              $erro .= implode(", ", $stmt->errorInfo());
           } 
    
            } ?>
        </fieldset>        
        </FORM>
        
    </BODY>
</HTML>