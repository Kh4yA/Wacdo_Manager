<?php

namespace App\Exceptions;

class RouteNotFound extends \Exception
{
    protected $message = 'La page que vous cherchez semble introuvable.';
    protected $code = 404;
}