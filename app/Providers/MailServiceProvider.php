<?php

namespace App\Providers;

use PHPMailer\PHPMailer\PHPMailer;
use League\Container\ServiceProvider\AbstractServiceProvider;

class MailServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'mail'
    ];

    public function register()
    {
        $this->getContainer()->add('mail', function () {
            $mail = new PHPMailer();

            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '0b61baf06f8e50';
            $mail->Password = 'ff87ef5f53ea30';
            $mail->Port = 25;

            $mail->setFrom('hello@concord.com', 'A8an7a из МОО «Содружество военных автомобилистов»');
            $mail->isHtml();

            return $mail;
        });
    }
}