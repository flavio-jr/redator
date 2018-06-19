<?php

namespace App\Exceptions;

class UserNotAllowedReceiveApplicationOwnershipTransfer extends \Exception
{
    public function __construct()
    {
        parent::__construct('The target user of the transfer can\'t receive an application ownership');
    }
}