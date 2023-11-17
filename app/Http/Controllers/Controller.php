<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *  title="API Documentation",
 *  version="0.1"
 * ),
 *
 * @OA\SecurityScheme(
 *  type="http",
 *  name="Authorization",
 *  in="header",
 *  scheme="bearer",
 *  securityScheme="bearerToken"
 * ),
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
