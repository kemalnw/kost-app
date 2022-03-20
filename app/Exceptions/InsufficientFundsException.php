<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InsufficientFundsException extends BadRequestHttpException
{
}
