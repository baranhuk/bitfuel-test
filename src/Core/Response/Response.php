<?php

namespace src\Core\Response;

class Response
{

    /**
     * Realiza o retondo da respota em json e código HTTP.
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com> 
     * @param array $data
     * @param int $code
     * @access public
     * @return void
     */
    static public function responseJson(array $data, int $code = 200)
    {
        http_response_code($code);
        echo json_encode($data);
        exit();
    }
}
