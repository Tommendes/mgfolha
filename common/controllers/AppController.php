<?php

/*
 * Conjunto de funções úteis a todas as classes
 */

namespace common\controllers;

use Yii;
use yii\helpers\Json;
use common\models\UserFiles;
use yii\helpers\Url;
use DateTime;
use PHPJasper\PHPJasper;

/**
 * Description of lynkos_base
 *
 * @author TomMe
 */
class AppController extends \yii\web\Controller {

    /**
     * Retorna o IP do cliente
     * @return type
     */
    public static function get_client_ip() {
        return Yii::$app->request->getUserIP();
    }

    /**
     * Remove preposições e artigos de uma string. Ideal para slugs
     * @param type $string
     * @param type $subst
     * @param type $asArray
     * @return type
     */
    public static function removerPreposicoesArtigos($string = "Branca de Neve e os Sete Anões", $subst = " ", $asArray = false) {
        $expressao = strip_tags($string);
        $palavrasSemPreposicao = str_ireplace([" de ", " da ", " do ", " na ", " no ",
            " em ", " a ", " o ", " e ", " as ", " os "], $subst, $expressao);
        if ($asArray) {
            return explode(" ", $palavrasSemPreposicao);
        } else {
            return $palavrasSemPreposicao;
        }
    }

    public static function remover_caracter($string) {
        $tr = strtr(
                $string, ['À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
            'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N',
            'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
            'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Ŕ' => 'R',
            'Þ' => 's', 'ß' => 'B', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
            'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y',
            'þ' => 'b', 'ÿ' => 'y', 'ŕ' => 'r'
        ]);

        return $tr;
    }

    /**
     * Limpa uma string json 
     * @param type $string
     * @return type
     */
    public static function limpa_json($string) {
        if (is_array($string)) {
            $string = Json::encode($string);
        }
        $tr = strtr(
                $string, [
//            '"' => '',
            '[' => '',
            '{' => '',
            '}' => '',
            ']' => '',
        ]);

        return $tr;
    }

    /**
     * Retorna a geolocalização baseada no endereço passado
     * @param type $endereco
     * @return type
     */
    public static function actionGetGeo($endereco) {
        // Get lat and long by address         
        $prepAddr = str_replace(' ', '+', $endereco);
        $geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?address=' . $prepAddr . '&sensor=false');
        $output = json_decode($geocode);
        $retorno = [
//            'output' => $output,
//            'address' => $prepAddr,
            'lat' => $output->results[0]->geometry->location->lat,
            'lng' => $output->results[0]->geometry->location->lng,
        ];
//        return Json::encode($retorno); // Para teste do webservice
        return ($retorno);
    }

    /**
     * Retorna a geolocalização baseada no endereço passado
     * @param type $ip
     * @return type
     */
    public static function actionGetIp_geo($ip = null) {
        // Get lat and long by address   
        $ipGeocode = file_get_contents("http://ipinfo.io/" . (strlen($ip) > 0 ? $ip . '/' : '') . "json");
        $output = json_decode($ipGeocode);
        $prepAddr = explode(',', $output->loc);
        $retorno = [
            'lat' => $prepAddr[0],
            'lng' => $prepAddr[1],
        ];
//        return Json::encode($retorno); // Para teste do webservice
        SisEventsController::registrarEvento('Localizado o IP ' . $ip . ': ' . Json::encode($retorno), 'actionGetIp_geo', Yii::$app->user->identity->username, 'SisEvents');
        return ($retorno);
    }

    /**
     * Retorna o timezone do google baseado nas coordenadas passadas
     * @param type $lat
     * @param type $lng
     * @return type
     */
    public static function actionGetTime_zone($lat, $lng) {
        // Get lat and long by address         
        $timezone = file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' .
                $lat . ',' . $lng . '&timestamp=' . time() . '&sensor=false');
        $output = json_decode($timezone);
        $retorno = [
            'timeZoneId' => $output->timeZoneId,
        ];
        if (Yii::$app->controller->id == 'app') {
            return Json::encode($retorno); // Para webservice
        } else {
            return ($retorno);
        }
    }

    public static function str_replace_first($from, $to, $subject) {
        $from = '/' . preg_quote($from, '/') . '/';
        return preg_replace($from, $to, $subject, 1);
    }

    /**
     * Capitaliza a primeira letra
     * @param type $string
     * @return type
     */
    public static function str_capitalizar($string) {
        return ucfirst($string);
    }

    public static function get_string_between($string, $start, $end) {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0)
            return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /**
     * Armazena uma foto recuperada por URL
     * Util para OAuth
     * @param type $url
     */
    public static function saveFile($user, $url) {
//        Get the file
        $content = file_get_contents("$url");
//        Store in the filesystem
        $dominio = $user->dominio;
        $user_pic = DIRECTORY_SEPARATOR . $dominio . '/users/' . str_replace(' ', '', strtolower($user->username));
        $user_pic_root = Yii::getAlias('@uploads_root') . $user_pic;
        if (!file_exists($user_pic_root)) {
            mkdir($user_pic_root, 0755, true);
        }
        $user_pic_root = $user_pic_root . DIRECTORY_SEPARATOR . 'face.jpg';
        $fp = fopen("$user_pic_root", "w");
        if (fwrite($fp, $content) && fclose($fp)) {
            return ['user_pic' => $user_pic];
        } else {
            return ['user_pic' => null];
        }
//        Yii::$app->session->setFlash('error', Yii::t('yii', $user_pic_root . '<br>' . $url));
    }

    /**
     * Salva a foto de perfil do usuário
     * @param type $user
     * @param type $url
     * @return boolean
     */
    public static function saveProfPhoto($user, $url) {
        $user_pic = self::saveFile($user, $url);
//        if ($user_pic['user_pic'] != null) {
        $dominio = $user->dominio;
        $user_dir = strtolower(str_replace(' ', '', trim(AppController::remover_caracter($user->username))));
        $filename = 'face.jpg';
        $model = UserFiles::findOne([
                    'id_user' => $user->id,
                    'basename' => 'face.jpg',
        ]);
        if (is_null($model)) {
            $model = new UserFiles([
                'slug' => self::setSlug(UserFiles::tableName()),
                'dominio' => $dominio,
                'created_at' => time(),
                'updated_at' => time(),
                'url' => Url::home(true) . Yii::getAlias('@uploads_url') . $user_pic['user_pic'],
                'src' => $user_dir . DIRECTORY_SEPARATOR . $filename,
                'title' => 'face.jpg',
                'basename' => $filename,
                'extension' => 'jpg',
                'id_user' => $user->id,
                'status' => UserFiles::STATUS_ATIVO,
                'evento' => SisEventsController::registrarEvento("Registro criado com sucesso!", 'saveProfPhoto', $user->username, 'user_files'),
            ]);
            if ($model->save()) {
//                Yii::$app->session->setFlash('user_file', $model);
//                Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully uploaded profile photo.'));
                return true;
            } else {
//                Yii::$app->session->setFlash('error', Yii::t('yii', 'Unsuccessfully uploaded profile photo. Error: {error}', ['error' => Json::encode($model->getErrors())]));
            }
        } else {
            $model->updated_at = time();
            $model->url = Url::home(true) . Yii::getAlias('@uploads_url') . $user_pic['user_pic'];
            $model->src = $user_dir . DIRECTORY_SEPARATOR . $filename;
            $model->title = 'face.jpg';
            $model->basename = $filename;
            $model->extension = 'jpg';
            $model->status = UserFiles::STATUS_ATIVO;
            $model->evento = SisEventsController::registrarEvento("Registro criado com sucesso!", 'saveProfPhoto', $user->username, 'user_files');

            if ($model->save()) {
//                Yii::$app->session->setFlash('user_file', $model);
//                Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated profile photo.'));
                return true;
            } else {
//                Yii::$app->session->setFlash('error', Yii::t('yii', 'Unsuccessfully updated profile photo. Error: {error}', ['error' => Json::encode($model->getErrors())]));
            }
        }
    }

    /**
     * Seta um slug para um determinado registro
     * @param type $tabela
     * @return type
     */
    public static function setSlug($tabela) {
        return strtolower(sha1($tabela . time()));
    }

    /**
     * Gets Folder and into files size
     * @param type $dir
     * @return type
     */
    public static function folderSize($dir) {
        $size = 0;
        foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : folderSize($each);
        }
        return $size;
    }

    /**
     * Converts bytes into human readable file size. 
     * 
     * @param string $bytes 
     * @return string human readable file size (2,87 Мб)
     * @author Mogilev Arseny 
     */
    public static function FileSizeConvert($bytes) {
        $bytes = floatval($bytes);
        $result = null;
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

    public function actionSess($sess_name, $sess_value = null) {
        if (!$sess_value == null) {
            Yii::$app->session->set($sess_name, $sess_value);
        }
        echo Yii::$app->session->get($sess_name);
    }

    public static function setCpfCnpjMask($cpfCnpj) {
        switch ($cpfCnpj) {
            case strlen($cpfCnpj) == 11 : $return = substr($cpfCnpj, 0, 3) . '.' .
                        substr($cpfCnpj, 3, 3) . '.' . substr($cpfCnpj, 6, 3) .
                        '-' . substr($cpfCnpj, 9, 2);
                break;
            case strlen($cpfCnpj) == 14 : $return = substr($cpfCnpj, 0, 2) . '.' .
                        substr($cpfCnpj, 2, 3) . '.' . substr($cpfCnpj, 5, 3) .
                        '.' . substr($cpfCnpj, 8, 4) . '-' . substr($cpfCnpj, 12, 2);
                break;
            default :$return = $cpfCnpj;
        }
        return (strlen($return) == 14 || strlen($return) == 18) ? $return : '';
    }

    public static function tratar_nome($nome) {
        $nome = strtolower($nome); // Converter o nome todo para minúsculo
        $nome = explode(" ", $nome); // Separa o nome por espaços
        $saida = '';
        for ($i = 0; $i < count($nome); $i++) {

            // Tratar cada palavra do nome
            if ($nome[$i] == "de" or $nome[$i] == "da" or $nome[$i] == "das" or $nome[$i] == "e" or $nome[$i] == "dos" or $nome[$i] == "do") {
                $saida .= $nome[$i] . ' '; // Se a palavra estiver dentro das complementares mostrar toda em minúsculo
            } else {
                $saida .= ucfirst($nome[$i]) . ' '; // Se for um nome, mostrar a primeira letra maiúscula
            }
        }
        return $saida;
    }

    /**
     * Converter data PT para EN
     * @param type $_date
     * @return boolean
     */
    public static function date_converter($_date = null) {
        $format = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
        $partes = null;
        if ($_date != null && \preg_match($format, $_date, $partes)) {
            return $partes[3] . '-' . $partes[2] . '-' . $partes[1];
        }
        return false;
    }

    /**
     * Retorna a diferença entre datas com em anos, meses e dias
     * @param type $data_nasc
     * @param type $data_final
     * @return type
     */
    public static function calc_idade($data_nasc, $data_final = null) {
// formato da data yyyy-mm-dd
        $date = new DateTime($data_nasc);
        $interval = $date->diff(new DateTime(($data_final != null ? $data_final : date("Y-m-d"))));
        return $interval->format('%Y ano(s), %m mes(es) e %d dia(s)');
    }

    /**
     * Print a resource
     * @param type $r
     * @param type $params
     * @param type $format
     * @return type
     */
    public static function setPrint($r, $destin_name, $destin_folder, $params, $format) {
        $dominio = strtolower(Yii::$app->user->identity->dominio);
        $output = Yii::getAlias('@uploads_root') . DIRECTORY_SEPARATOR . $dominio . DIRECTORY_SEPARATOR . 'reports';
        if (!file_exists($output . DIRECTORY_SEPARATOR . $destin_folder)) {
            mkdir($output . DIRECTORY_SEPARATOR . $destin_folder, 0777, true);
        }
        Yii::setAlias('reports', '@frontend/reports/');
        $jasper = new PHPJasper;
        $input = Yii::getAlias('@reports') . "$r.jrxml";
        $username = Yii::$app->db->username;
        $password = Yii::$app->db->password;
        $options = [
            'format' => $format,
            'locale' => 'pt_BR',
            'params' => $params,
            'db_connection' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'port' => '3306',
                'database' => $username,
                'username' => $username,
                'password' => $password,
            ]
        ];
        $destin_file = $output . DIRECTORY_SEPARATOR . $destin_folder . DIRECTORY_SEPARATOR . $destin_name;
//        print_r($jasper);
//        echo $jasper->process($input, $output . DIRECTORY_SEPARATOR . $destin_folder . DIRECTORY_SEPARATOR . $destin_name, $options)->output();
        $jasper->process($input, $destin_file, $options)->execute();
        return Yii::$app->response->sendFile($destin_file . '.pdf', null
                        , ['inline' => true, 'mimeType' => 'application/pdf']);
    }

    public static function getExtenso($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false) {

        $singular = null;
        $plural = null;

        if ($bolExibirMoeda) {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        } else {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

        if ($bolPalavraFeminina) {

            if ($valor == 1) {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            } else {
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            }


            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas", "quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }

        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);

        for ($i = 0; $i < count($inteiro); $i++) {
            for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++) {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")
                $z++;
            elseif ($z > 0)
                $z--;

            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];

            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        $rt = mb_substr($rt, 1);

        return($rt ? trim($rt) : "zero");
    }

}
