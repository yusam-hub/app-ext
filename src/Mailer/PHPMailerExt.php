<?php

namespace YusamHub\AppExt\Mailer;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use YusamHub\AppExt\Helpers\ExceptionHelper;
use YusamHub\AppExt\Traits\GetSetLoggerTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetLoggerInterface;

class PHPMailerExt extends PHPMailer implements GetSetLoggerInterface
{
    use GetSetLoggerTrait;

    /**
     * @throws Exception
     */
    public function __construct(array $config)
    {
        $this->isSMTP();
        $this->SMTPDebug = $config['debug']??0;
        $this->Host = $config['host']??'';
        $this->Username = $config['user']??'';
        $this->Password = $config['pass']??'';
        $this->Port = $config['port']??25;
        $this->SMTPAuth = !empty($this->Username) && !empty($this->Password);
        $this->SMTPSecure = $config['secure']??'';
        $this->setFrom($config['fromAddress']??'',$config['fromName']??'');

        parent::__construct(true);
    }

    public function getFromAddress(): string
    {
        return $this->From;
    }

    public function getFromName(): string
    {
        return $this->FromName;
    }

    /**
     * @param string $toEmail
     * @param string $subject
     * @param string $body
     * @param string|null $altBody
     * @return bool
     * @throws Exception
     * @throws \Throwable
     */
    public function sendTo(string $toEmail, string $subject, string $body, ?string $altBody = null): bool
    {
        try {
            $mail = clone $this;
            $mail->addAddress($toEmail);
            $mail->Subject = $subject;
            $mail->Body = $body;
            if (!is_null($altBody)) {
                $mail->AltBody = $altBody;
            }
            $mail->isHTML(!is_null($altBody));
            return $mail->send();
        } catch (\Throwable $e) {
            if ($this->hasLogger()) {
                $this->getLogger()->error($e->getMessage(), ExceptionHelper::e2a($e));
                return false;
            } else {
                throw $e;
            }
        }
    }
}