<?php

namespace App\Exceptions;

class ErrorConnexion extends \Exception
{
    protected $message = 'Mot de passe ou login incorrect.';
}