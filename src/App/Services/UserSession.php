<?php

namespace App\Services;

use App\Entities\User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class UserSession
{
    const EXPIRATION_TIME = 3600;

    public function createNewToken(string $id): string
    {
        $builder = new Builder();

        $signer = new Sha256();

        $token = $builder
            ->setIssuer('')
            ->setAudience('')
            ->setId($id, true)
            ->setIssuedAt(time())
            ->setNotBefore(time())
            ->setExpiration(time() + self::EXPIRATION_TIME)
            ->sign($signer, $id)
            ->getToken();
        
        return (string) $token;
    }

    public function isValidToken(string $token): bool
    {
        $signer = new Sha256();
        $parser = new Parser();

        $token = $parser->parse($token);

        if (!$token->verify($signer, $token->getHeader('jti'))) {
            return false;
        }

        $data = new ValidationData();
        $data->setId($token->getHeader('jti'));

        return $token->validate($data);
    }
}