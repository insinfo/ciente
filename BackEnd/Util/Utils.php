<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 07/08/2017
 * Time: 10:34
 */

namespace Ciente\Util;

use Ciente\Model\VO\Exception\File\AnexoException;
use \Exception;
use Ciente\Util\Exceptions\NoInternetException;
use DateTime;

class Utils
{
    /** Verifica se a internet conection por ping **/
    public static function checkInternet1()
    {
        $result = false;
        exec("ping -c 4 www.google.com.br", $output, $status);
        if ($status == 0)
        {
            // ping succeeded, we have connection working
            $result = true;
        }
        else
        {
            // ping failed, no connection
            $result = false;
        }

        if (fsockopen("www.google.com.br", 80))
        {
            // "Could not open www.google.com, connection issues?";
            $result = true;
        }
        else
        {
            $result = false;
        }
        return $result;
    }

    /** Verifica se a internet conection por network socket **/
    public static function checkInternet2()
    {
        $connected = @fsockopen("www.google.com.br", 80);
        //website, port  (try 80 or 443)
        if ($connected)
        {
            $is_conn = true; //action when connected
            fclose($connected);
        }
        else
        {
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }

    /** Obtem a data e hora atual no horario de brasilia **/
    private static $datetime = null;

    public static function getDateTimeNow($format = "BR", $force_update = false)
    {
        // DEFINE O FUSO HORARIO COMO O HORARIO DE SÃO PAULO
        date_default_timezone_set('America/Sao_Paulo');

        if (self::$datetime == null || $force_update) {
            self::$datetime = time();
        }

        if ($format !== "BR") {
            return date('Y-m-d H:i:s', self::$datetime);
        } else {
            return date('d/m/Y H:i:s', self::$datetime);
        }
    }

    public static function getDateNow($format='BR')
    {
        // DEFINE O FUSO HORARIO COMO O HORARIO DE SÃO PAULO
        date_default_timezone_set('America/Sao_Paulo');

        // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
        return date('d/m/Y', time());
    }

    //converte data no formato SQL para o formato de data Brasileiro
    public static function SQLDateToBrasilDate($source)
    {
        if (self::isDate($source))
        {
            date_default_timezone_set('America/Sao_Paulo');
            //$date = DateTime::createFromFormat('Y-m-d', $source);
            $date = new DateTime($source);
            return $date->format('d/m/Y');
        }
        return $source;

        /*$pattern = '/\d+/';
        preg_match_all($pattern, $source, $matches, PREG_SET_ORDER);
        return $matches[2][0] . '/' . $matches[1][0] . '/' . $matches[0][0];*/
        // return date('d/m/Y',strtotime($source));
    }

    public static function isDate($value)
    {
        if (!$value)
        {
            return false;
        }

        try
        {

            if (DateTime::createFromFormat('Y-m-d', $value) !== false)
            {
                return true;
            }

            new \DateTime($value);
            return true;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    /** cliente WEB **/
    public static function simpleCurl($url, $data = null, $methodHTTP = 'POST', $apiType = 'restfull')
    {
        $result = null;
        $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.104 Safari/537.36';

        $curl = curl_init();

        switch ($methodHTTP)
        {
            case "POST":
            {
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                }
                break;
            }
            case "PUT":
            {
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            }
            case "GET":
            {
                if ($data)
                {
                    if ($apiType == 'restfull')
                    {
                        $url .= $data;
                    }
                    else
                    {
                        $url .= '?' . http_build_query($data);
                    }
                }
            }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_USERAGENT, $agent);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_VERBOSE, true);

        $result = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($curl);
        curl_close($curl);

        if ($curl_errno)
        {
            if ($curl_errno == 6 || $curl_errno == 7)
            {
                throw new NoInternetException();
            }
            else
            {
                throw new Exception('CURL: ' . $curl_errno . ' HTTP: ' . $http_status . ' ', 400);
            }
        }
        return $result;
    }

    /** retira acento da string e deixa o caractere sem acento **/
    public static function removeAccents($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
    }

    /** Retorna Estados Brasileiros ou União Federativa **/
    const STATES_OF_BRASIL = array('AC' => 'Acre', 'AL' => 'Alagoas', 'AM' => 'Amazonas', 'AP' => 'Amapá', 'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo', 'GO' => 'Goiás', 'MA' => 'Maranhão', 'MG' => 'Minas Gerais', 'MS' => 'Mato Grosso do Sul', 'MT' => 'Mato Grosso', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PE' => 'Pernambuco', 'PI' => 'Piauí', 'PR' => 'Paraná', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'RS' => 'Rio Grande do Sul', 'SC' => 'Santa Catarina', 'SE' => 'Sergipe', 'SP' => 'São Paulo', 'TO' => 'Tocantins',);

    /** filtra um array atravez de outro array **/
    public static function filterArrayByArray($arrayToBeFiltered,$arrayFilter)
    {
        $result = array();
        foreach ($arrayFilter as $item)
        {
            if (array_key_exists($item,$arrayToBeFiltered))
            {
                $result[$item] = $arrayToBeFiltered[$item];
            }
        }
        return $result;
    }

    // Function for basic field validation (present and neither empty nor only white space
    public static function isNullOrEmptyString($string)
    {
        return (!isset($string) || trim($string) === "" || trim($string) === 'null' || $string == null);
    }

    public static function adAspas($str, $aspas = '"') {
        return $aspas . $str . $aspas;
    }

    /**
     * Template simples para mensagem
     *
     * @param $message
     * @param $args array
     * @return string
     */

    public static function messageTemplate ($message, array $args) {
        foreach ($args as $key => $arg) {
            $message = str_replace('{'. $key.'}', $arg, $message);
        }
        return $message;
    }

    /********************* FILE MANAGE **************************/
    /**
     * salva uma string URI base64 do JavaScript File API para arquivo
     *
     * @param $dataURIBase64String
     * @param $filePath
     * @param null $fileName
     * @param array $valid_types
     * @return string
     * @throws Exception
     */
    public static function base64ToFileSave($dataURIBase64String, $filePath, $fileName=null, $valid_types = array())
    {

        if (!preg_match('/^data:([a-z0-9][a-z0-9\\!\\#\\$\\&\\-\\^\\_\\+\\.]{0,126}\\/[a-z0-9][a-z0-9\\!\\#\\$\\&\\-\\^\\_\\+\\.]{0,126}(;[a-z0-9\\-]+\\=[a-z0-9\\-]+)?)?(;base64)?,[a-z0-9\\!\\$\\&\\\'\\,\\(\\)\\*\\+\\,\\;\\=\\-\\.\\_\\~\\:\\@\\/\\?\\%\\s]*\\s*$/i', $dataURIBase64String)) {
            throw new Exception('The provided "data:" URI is not valid.',400);
        }

        $mimeType = substr($dataURIBase64String, 5, strpos($dataURIBase64String, ';')-5);
        list ($type, $extension) = preg_split('/\//m', $mimeType);

        $isValidType = false;
        foreach ($valid_types as $ftype ) {

            if ($ftype === $extension) {
                $isValidType = true;
                break;
            }

        }

        if ($isValidType && boolval($valid_types)) {
            throw new Exception("Type file is not valid");
        }

        $better_token = md5(uniqid(rand(), true));
        $fileName = $fileName ? $fileName :  $better_token;
        $filePathWithName =  $filePath .'/'. $fileName .'.'. $extension;

        /*$fp   = fopen($dataURIBase64String, 'r');
        $meta = stream_get_meta_data($fp);
        ///storage/profile/userDefault.jpeg
        $meta['mediatype'];*/
        file_put_contents($filePathWithName, file_get_contents($dataURIBase64String));

        return $fileName .'.'. $extension;

    }
    //obtem o tipo de arquivo de uma string URI base64
    public static function getDataURIBase64Type($str) {
        // $str should start with 'data:' (= 5 characters long!)
        return substr($str, 5, strpos($str, ';')-5);
    }
    //obtem a extenção de arquivo de uma string URI base64
    public static function getDataURIBase64Extension($dataURIBase64String) {
        // $str should start with 'data:' (= 5 characters long!)
        $mimeType = substr($dataURIBase64String, 5, strpos($dataURIBase64String, ';')-5);
        list ($type, $extension) = preg_split('/\//m', $mimeType);
        return $extension;
    }

    public static function isDataURI($dataURIBase64String){

        if($dataURIBase64String == null){
            return false;
        }

        if($dataURIBase64String == ''){
            return false;
        }

        if($dataURIBase64String == 'null'){
            return false;
        }

        if (!preg_match('/^data:([a-z0-9][a-z0-9\\!\\#\\$\\&\\-\\^\\_\\+\\.]{0,126}\\/[a-z0-9][a-z0-9\\!\\#\\$\\&\\-\\^\\_\\+\\.]{0,126}(;[a-z0-9\\-]+\\=[a-z0-9\\-]+)?)?(;base64)?,[a-z0-9\\!\\$\\&\\\'\\,\\(\\)\\*\\+\\,\\;\\=\\-\\.\\_\\~\\:\\@\\/\\?\\%\\s]*\\s*$/i', $dataURIBase64String)) {
            return false;
        }
        return true;
    }

    public static function deleteFile($filePath){
        if($filePath != null || trim($filePath) != "")
        {
            if (file_exists($filePath))
            {
                unlink($filePath);
            }
        }
    }

    //obtem um id unico não sequencial
    public static function getUniqueId(){
        return md5(uniqid(rand(), true));
    }
}

