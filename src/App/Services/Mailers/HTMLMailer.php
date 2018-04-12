<?php

namespace App\Services\Mailers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Services\HtmlSanitizer;

/**
 * Send email in HTML format
 * @class HTMLMailer
 */
class HTMLMailer implements MailerInterface
{
    /**
     * The classic PHP mailer lib
     * @var PHPMailer
     */
    private $mailer;

    /**
     * The mail sender
     * @var string
     */
    private $from;

    /**
     * The mail receiver
     * @var string
     */
    private $to;

    /**
     * The HTML path for the email body
     * @var string
     */
    private $templatePath;

    /**
     * The mail subject
     * @var string
     */
    private $subject;

    /**
     * Service for clean html
     * @var HtmlSanitizer
     */
    private $htmlSanitizer;

    public function __construct(
        PHPMailer $mailer,
        HtmlSanitizer $htmlSanitizer
    ) {
        $this->mailer = $mailer;
        $this->htmlSanitizer = $htmlSanitizer;

        $this->buildFromEnvironment();
    }

    /**
     * Build the configuration from the environment
     * @method buildFromEnvironment
     */
    private function buildFromEnvironment()
    {
        $this->mailer->Host = getenv('MAIL_HOST');
        $this->mailer->SMTPAuth = getenv('MAIL_SMTP_AUTH_ENABLED');
        $this->mailer->Username = getenv('MAIL_USER');
        $this->mailer->Password = getenv('MAIL_PASSWORD');
        $this->mailer->SMTPSecure = getenv('MAIL_ENCRYPT');
        $this->mailer->Port = getenv('MAIL_PORT');

        $this->mailer->isHTML(true);
        $this->mailer->isSMTP();
    }

    /**
     * Set the mail sender
     * @method from
     * @param string $from
     * @return self
     */
    public function from(string $from): MailerInterface
    {
        $this->mailer->setFrom($from);
        return $this;
    }

    /**
     * Set the mail receiver
     * @method to
     * @param string $to
     * @return self
     */
    public function to(string $to): MailerInterface
    {
        $this->mailer->addAddress($to);
        return $this;
    }

    /**
     * Set the email subject
     * @method subject
     * @param string $subject
     * @return self
     */
    public function subject(string $subject): MailerInterface
    {
        $this->mailer->Subject = $subject;
        return $this;
    }

    /**
     * Set the email body
     * @method body
     * @param string $body
     * @return self
     */
    public function body(string $body): MailerInterface
    {
        $this->mailer->Body = $this->htmlSanitizer->sanitize($body);
        return $this;
    }

    /**
     * Sends the email
     * @method send
     * @return bool
     */
    public function send(): bool
    {
        return $this->mailer->send();
    }
}