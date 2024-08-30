<?php

namespace App\Exceptions;

class ForbiddenPage extends \Exception
{
    protected $message = 'La page que vous cherchez est interdite.';
    protected $code = 403;
}