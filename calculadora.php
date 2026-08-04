<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h1>Projeto calculadora</h1>
        <form action="" method = "post">
            <label for="ano">Valor 1:</label>
            <input type="text" name = "vlr1">
            <label for="nasc">Valor 2:</label>
            <input type="text" name = "vlr2">

            <select name="operacao" id="operacao">
                <option value="soma">Soma</option>
                <option value="subtracao">Subtracao</option>
                <option value="multiplicacao">Multiplicacao</option>
                <option value="divisao">Divisao</option>
            </select>
            <button type = "submit">Calcular</button>
        </form>

        <?php
        if($_POST){
            $valor1   = $_POST['vlr1'];
            $valor2   = $_POST['vlr2'];
            $operacao = $_POST['operacao'];

            echo "<h2>Resultado:</h2>";

            switch ($operacao){
                case 'soma':
                    echo "<p>$valor1 + $valor2 = ".($valor1 + $valor2) ."</p>";
                    break;

                case 'subtracao':
                    echo "<p>$valor1 - $valor2 = ".($valor1 - $valor2) ."</p>";
                    break;

                case 'divisao':
                    if($valor2 != 0){
                        echo "<p>$valor1 / $valor2 = ".($valor1 / $valor2) ."</p>";
                    }else{
                        echo "<p>Erro: Divisão por zero não é permitida.</p>";
                    }    
                    break;

                case 'multiplicacao':
                    echo "<p>$valor1 * $valor2 = ".($valor1 * $valor2) ."</p>";
                    break;
            }   
        }else{
            echo "<h2>Resultado:</h2>";
            echo "<p>Aguardando cálculo...</p:";
        }
        ?>
    </div>
</body>
</html>