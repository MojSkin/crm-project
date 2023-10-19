<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function normalizeError($error)
    {
        $errors = [];
        foreach ($error as $e) {
            foreach ($e as $message) {
                $errors[] = $message;
            }
        }
        return $errors;
    }
}
