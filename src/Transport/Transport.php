<?php

namespace Emeow\Transport;

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