<?php

namespace App\Services\Mailers;

interface MailerInterface
{
    public function from(string $from): MailerInterface;

    public function to(string $to): MailerInterface;

    public function subject(string $subject): MailerInterface;

    public function body(string $body): MailerInterface;

    public function send(): bool;
}