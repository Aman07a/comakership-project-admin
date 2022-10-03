<?php

namespace App\Helpers;

class ArrayHelpers
{
    public static function createArrayResponse($is_error, $code, $message, $content)
    {
        $result = [];

        if ($is_error) {
            $result['success'] = false;
            $result['code'] = $code;
            $result['message'] = $message;
        } else {
            $result['success'] = true;
            $result['code'] = $code;

            if ($content == null) {
                $result['message'] = $message;
            } else {
                $result['response'] = $content;
            }
        }

        return $result;
    }
}
