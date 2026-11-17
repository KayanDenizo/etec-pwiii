<?php
//recebe as fotos enviadas no campo foto[] do formulario,
//move cada uma para a pasta imagens/ e devolve o vetor com os nomes novos.
//arquivos que nao sao JPG ou PNG sao ignorados e geram uma mensagem em $erros.
function salvarFotos(&$erros){
    //cria um array vazio para guardar os nomes das fotos caso tenha enviado
    $fotos = array();

    //checa se foi enviada alguma foto
    if (!isset($_FILES['foto'])) {
        return $fotos;
    }

    //cria um laco e repete enquanto houver fotos
    for ($i = 0; $i < count($_FILES['foto']['name']); $i++) {
        //pula o campo vazio (nenhum arquivo escolhido)
        if ($_FILES['foto']['error'][$i] == UPLOAD_ERR_NO_FILE) {
            continue;
        }

        if ($_FILES['foto']['type'][$i] == "image/png") {
            $tipo = ".png";
        } elseif ($_FILES['foto']['type'][$i] == 'image/jpeg') {
            $tipo = ".jpg";
        } else {
            $tipo = "outro";
        }

        if ($tipo == 'outro') {
            $erros[] = "O arquivo \"" . $_FILES['foto']['name'][$i] . "\" foi ignorado: só é possível enviar JPG e PNG.";
        } else {
            $nome_arquivo = md5($_FILES['foto']['name'][$i]) . rand(1, 999) . $tipo; //encripta

            //move o arquivo para a pasta imagens ja com o nome novo do arquivo
            move_uploaded_file($_FILES['foto']['tmp_name'][$i], 'imagens/' . $nome_arquivo);

            //armazena o nome do arquivo no vetor fotos
            array_push($fotos, $nome_arquivo);
        }
    }

    return $fotos;
}

//apaga o arquivo de uma imagem da pasta imagens/
function apagarArquivoImagem($nome_imagem){
    $caminho = 'imagens/' . basename($nome_imagem);
    if (is_file($caminho)) {
        unlink($caminho);
    }
}
