<?php

$newURL = $_COOKIE['newURL']; 
$zip = new ZipArchive;
$folder = __DIR__;
$filetounzip = 'supd.upd';
if ($zip->open($folder . DIRECTORY_SEPARATOR . $filetounzip) === TRUE) {
    set_time_limit(360);
    $zip->extractTo($folder);
    $zip->close();
    unlink($folder . DIRECTORY_SEPARATOR . $filetounzip);
    setcookie('_sums', 'Aplicação atualizada com sucesso.<br>'
            . 'Para saber mais clique <a href="https://megassessoriaetecnologia.com.br/folha/sis-reviews" target="_blank">aqui</a>');
    header('Location: ' . $newURL);
} else {
    $path_parts = pathinfo(__FILE__);
    $arquivo = $path_parts['filename'] . '.' . $path_parts['extension'];
    setcookie('_sums', 'Falha ao tentar atualizar a aplicação. Erro em: ' . $arquivo);
}