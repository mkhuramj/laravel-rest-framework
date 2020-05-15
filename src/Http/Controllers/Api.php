<?php

/**
 * Created By: Visual Studio Code 1.44.2
 * Name; Api
 * Date: May 04, 2020
 * Time: 09:46:097 AM
 * @author Khuram Javed <m.khuramj@gmail.com>
 */

namespace LaravelRestFramework\Http\Controllers;

use LaravelRestFramework\Services\HttpStatus;

class Api
{

    public static function response($data, $status = HttpStatus::OK)
    {
        return response()->json([
            'success' => TRUE,
            'data' => $data
        ], $status);
    }

    public static function collectionResponse($data, $status = HttpStatus::OK)
    {
        $data['success'] = TRUE;
        return response()->json($data, $status);
    }

    public static function problem($message, $errors = NULL, $status = HttpStatus::BAD_REQUEST)
    {
        return response()->json([
            'success' => FALSE,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }

    public static function paginate($data)
    {
        if (!is_array($data)) {
            $data = $data->toArray();
        }
        $data['success'] = true;
        return response()->json($data);
    }
}