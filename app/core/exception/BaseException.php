<?php

namespace app\core\exception;

use Exception;

/*
|--------------------------------------------------------------------------
| Exception
|--------------------------------------------------------------------------
| This class aims to hold all exception messages 
|
*/ 

class BaseException extends Exception
{
    /** 
     * Take an exception message and return the result in json form  
     * 
     * @param object $error  
     * @return json
    */
    public static function getException($error)
    {
        $message = 
        array(
            'status'  => false,
            'message' => $error->getMessage(),
            'code'    => $error->getCode(),
            'file'    => $error->getFile(),
            'line'    => $error->getLine(),
            'errors'  => 
            array (
                'trace' => $error->getTrace()
            )
        );

        header('Content-Type: application/json');
        echo json_encode($message);
        exit;
    }
}