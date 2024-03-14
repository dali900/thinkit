<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Resource not found respons with message
     *
     * @param string $msg 
     * @return \Illuminate\Http\Response
     */
    public function responseNotFound(string $msg = "Resource not found")
    {
        $data = [
            "message" => $msg
        ];

        return response()->json($data, Response::HTTP_NOT_FOUND);
    }

    /**
     * server error
     *
     * @param string $msg = 'Error'
     * @param array $errors = []
     * @return \Illuminate\Http\Response
     */
    public function responseErrorMsg(string $msg = "Error", array $errors = [])
    {
        $data = [
            "message" => $msg,
            "errors" => $errors
        ];

        return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Success response message
     *
     * @param array $data = []
     * @param integer $code = 200
     * @return \Illuminate\Http\Response
     */
    public function responseSuccess($data = [], $code = Response::HTTP_OK)
    {
        return response()->json($data, $code);
    }

    /**
     * Success response message
     *
     * @param array $data = []
     * @return \Illuminate\Http\Response
     */
    public function responseCreated($data = [])
    {
        return response()->json($data, Response::HTTP_CREATED);
    }

    /**
     * Success response message
     *
     * @param array $data = []
     * @return \Illuminate\Http\Response
     */
    public function responseNoContent($data = ['message' => 'Success'])
    {
        return response()->json($data, Response::HTTP_NO_CONTENT);
    }

    /**
     * Unauthorized access
     *
     * @param array $data = []
     * @return \Illuminate\Http\Response
     */
    public function responseUnauthorized($data = [])
    {
        return response()->json($data, Response::HTTP_UNAUTHORIZED);
    }
    
    /**
     * Forbidden access
     *
     * @param array $data = []
     * @return \Illuminate\Http\Response
     */
    public function responseForbidden($data = ['message' => 'Access Denied'])
    {
        return response()->json($data, Response::HTTP_FORBIDDEN);
    }
}
