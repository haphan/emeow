<?php

namespace Emeow\Transport;

use Symfony\Component\Mailer\Exception\UnsupportedSchemeException;
use Symfony\Component\Mailer\Transport\AbstractTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;

final class GmailTransportSourceIpAwareFactory extends AbstractTransportFactory
{
    public function create(Dsn $dsn): TransportInterface
    {
        if (\in_array($dsn->getScheme(), $this->getSupportedSchemes())) {
            $transport = new GmailSmtpTransport($this->getUser($dsn), $this->getPassword($dsn), $this->dispatcher, $this->logger);
            $transport->getStream()->setSourceIp($dsn->getOption('ip'));
            return $transport;
        }

        throw new UnsupportedSchemeException($dsn, 'gmail', $this->getSupportedSchemes());
    }

    protected function getSupportedSchemes(): array
    {
        return ['gmail+stream'];
    }
}
