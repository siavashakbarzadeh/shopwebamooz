<?php

namespace Jokoli\Common\Responses;

use Illuminate\Support\Facades\Response;

class AjaxResponses
{
    public static function successResponse($data = [])
    {
        return Response::json(array_merge(['message' => "عملیات با موفقیت انجام شد"],$data));
    }

    public static function errorResponse($data = [])
    {
        return Response::json(array_merge(['message' => "خطا در انجام عملیات"],$data), 500);
    }

    public static function notFoundResponse($data = [])
    {
        return Response::json(array_merge(['message' => "خطا در انجام عملیات"], $data), 404);
    }
}
