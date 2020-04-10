<?php

namespace Emeow;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MimeTypesInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\Mailer\Mailer;
use Emeow\Transport\Transport;

class EmailManager
{
    private $defaultTemplate = 'email.twig';
    private $template = null;


    public function render(string $recipient, string $subject, string $sender, array $params = [], $html = false): Email
    {
        $twig = new Environment(new FilesystemLoader(getcwd()), ['cache' => sys_get_temp_dir()]);

        $body =  $twig->load($this->template ?? $this->defaultTemplate)->render($params);

        $email = (new Email())
            ->from($sender)
            ->to($recipient)
            ->subject($subject)
        ;

        if($html) {
            $email->html($body);
        } else {
            $email->text($body);
        }

        return $email;
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    public function send(string $dsn, Email $email)
    {
        $transport = Transport::fromDsn($dsn);
        $mailer = new Mailer($transport);
        $mailer->send($email);
    }
}