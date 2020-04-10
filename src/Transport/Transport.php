<?php

namespace Emeow\Transport;

use Symfony\Component\Mailer\Bridge\Amazon\Transport\SesTransportFactory;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailTransportFactory;
use Symfony\Component\Mailer\Bridge\Mailchimp\Transport\MandrillTransportFactory;
use Symfony\Component\Mailer\Bridge\Mailgun\Transport\MailgunTransportFactory;
use Symfony\Component\Mailer\Bridge\Postmark\Transport\PostmarkTransportFactory;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransportFactory;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\Transport as BaseTransport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class Transport extends BaseTransport
{
    private const FACTORY_CLASSES = [
        SesTransportFactory::class,
        GmailTransportFactory::class,
        MandrillTransportFactory::class,
        MailgunTransportFactory::class,
        PostmarkTransportFactory::class,
        SendgridTransportFactory::class,
        GmailTransportSourceIpAwareFactory::class,
    ];

    public static function fromDsn(string $dsn, EventDispatcherInterface $dispatcher = null, HttpClientInterface $client = null, LoggerInterface $logger = null): TransportInterface
    {
        $factory = new self(self::getDefaultFactories($dispatcher, $client, $logger));

        return $factory->fromString($dsn);
    }

    private static function getDefaultFactories(EventDispatcherInterface $dispatcher = null, HttpClientInterface $client = null, LoggerInterface $logger = null): iterable
    {
        foreach (self::FACTORY_CLASSES as $factoryClass) {
            if (class_exists($factoryClass)) {
                yield new $factoryClass($dispatcher, $client, $logger);
            }
        }

        yield new BaseTransport\NullTransportFactory($dispatcher, $client, $logger);

        yield new BaseTransport\SendmailTransportFactory($dispatcher, $client, $logger);

        yield new BaseTransport\Smtp\EsmtpTransportFactory($dispatcher, $client, $logger);
    }
}