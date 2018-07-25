<?php

namespace App\Services\UserSession;

use App\Entities\User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class UserSession implements UserSessionInterface
{
    /**
     * @inheritdoc
     */
    public function createNewToken(string $id): string
    {
        $builder = new Builder();

        $signer = new Sha256();
        $secret = config()['app']['token_secret'];

        $token = $builder
            ->setIssuer('')
            ->setAudience('')
            ->setId($secret, true)
            ->setIssuedAt(time())
            ->setNotBefore(time())
            ->setExpiration(time() + self::EXPIRATION_TIME)
            ->set('user', $id)
            ->sign($signer, $secret)
            ->getToken();
        
        return (string) $token;
    }

    /**
     * @inheritdoc
     */
    public function isValidToken(string $token): bool
    {
        $signer = new Sha256();
        $parser = new Parser();

        $token = $parser->parse($token);

        if (!$token->verify($signer, config()['app']['token_secret'])) {
            return false;
        }

        $data = new ValidationData();

        return $token->validate($data);
    }
}