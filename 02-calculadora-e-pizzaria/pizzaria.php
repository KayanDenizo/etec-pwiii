<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto Pizzaria RadioButton e CheckBox</title>
    <style>

        *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body{
    background:linear-gradient(135deg,#fff3e0,#ffe0b2);
    min-height:100vh;
    display:flex;
    flex-direction:column;
    align-items:center;
    padding:40px;
}

h1{
    color:#c62828;
    margin-bottom:25px;
    font-size:35px;
    text-shadow:2px 2px 8px rgba(0,0,0,.15);
}

form{
    width:520px;
    background:#fff;
    padding:30px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
    transition:.3s;
}

form:hover{
    transform:translateY(-3px);
}

label{
    font-size:18px;
    font-weight:bold;
    color:#444;
}

select{
    width:100%;
    margin-top:10px;
    margin-bottom:20px;
    padding:12px;
    border-radius:10px;
    border:2px solid #ff9800;
    font-size:16px;
    outline:none;
}

fieldset{
    border:2px solid #ff9800;
    border-radius:12px;
    padding:18px;
    margin-bottom:20px;
}

legend{
    color:#d84315;
    font-weight:bold;
    padding:0 8px;
}

input{
    margin:8px;
}

button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:12px;
    background:linear-gradient(90deg,#e53935,#fb8c00);
    color:white;
    font-size:18px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    transform:scale(1.03);
    box-shadow:0 8px 20px rgba(229,57,53,.35);
}

.resultado{
    width:520px;
    margin-top:35px;
    background:#fff;
    padding:25px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
    text-align:center;
    animation:aparecer .7s;
}

.resultado h2{
    color:#d84315;
    margin-bottom:15px;
}

.resultado p{
    font-size:18px;
    margin:10px 0;
}

.total{
    margin-top:20px;
    color:#c62828;
    font-size:28px;
    font-weight:bold;
}




        
    </style>
</head>
<body>
    <div id="pizza">🍕</div>
    <h1>Projeto Pizzaria RadioButton e CheckBox</h1>

    <form method="post" action="">
        <label for="sabor">Sabor da pizza:</label>
        <select name="sabor" id="sabor">
            <option value="1">Frango com Catupiri - R$ 30,00</option>
            <option value="2">Muzzarela - R$ 25,00</option>
            <option value="3">Calabrezza - R$ 35,00</option>
            <option value="4">Escarola com Bacon - R$ 40,00</option>
        </select>

        <fieldset>
            <legend>Adicionais:</legend>
            <input type="radio" name="adicional" value="1">
            Borda de Catupiri - R$ 5,00 <br>
            <input type="radio" name="adicional" value="2">
            Borda de Cheddar - R$ 7,00 <br>
        </fieldset>

        <fieldset>
            <legend>Refrigerantes:</legend>
            <input type="checkbox" name="coca" value="1">
            Coca-Cola - R$ 8,00 <br>
            <input type="checkbox" name="guarana" value="2">
            Guaraná - R$ 6,00 <br>
        </fieldset>

        <button type="submit">Calcular</button>
    </form>

    <?php
    function calcula(){
        if( $_POST ){
            $sabor        = $_POST['sabor'];
            $valor_sabor  = 0.00;
            $pedido       = "";

            if(isset($_POST['coca'])){
                $valor_coca = 8.00;
                $pedido    .= "Coca-Cola ";
            } else {
                $coca        = null;
                $valor_coca  = 0.00;
            }

            if(isset($_POST['guarana'])){
                $valor_guarana = 6.00;
                $pedido       .= "Guaraná ";
            } else {
                $guarana       = null;
                $valor_guarana = 0.00;
            }

            if( isset($_POST['adicional']) ){
                $adicional = $_POST['adicional'];
            } else {
                $adicional = null;
            }

            if( $adicional == "1" ){
                $valor_adicional = 5.00;
                $pedido         .= "Borda de Catupiri ";
            } else if( $adicional == "2" ){
                $valor_adicional = 7.00;
                $pedido         .= "Borda de Cheddar ";
            } else {
                $valor_adicional = 0.00;
            }

            $valor_sabor += $valor_adicional + $valor_coca + $valor_guarana;

            switch ($sabor) {
                case '1':
                    $sabor       = "Frango com Catupiri - R$ 30,00";
                    $valor_sabor += 30.00;
                    break;
                case '2':
                    $sabor       = "Muzzarela - R$ 25,00";
                    $valor_sabor += 25.00;
                    break;
                case '3':
                    $sabor       = "Calabrezza - R$ 35,00";
                    $valor_sabor += 35.00;
                    break;
                case '4':
                    $sabor       = "Escarola com Bacon - R$ 40,00";
                    $valor_sabor += 40.00;
                    break;
            }

            echo "<h2>Pedido:</h2>";
            echo "<p>Sabor: $sabor </p>";
            echo "<p>Adicionais: $pedido </p>";
            echo "<h2>Valor Total: R$ " . number_format($valor_sabor, 2, ',', '.') . "</h2>";
        }
    }

    // AQUI ESTAVA FALTANDO: chamar a função para rodar!
    calcula();
    ?>

</body>
</html>