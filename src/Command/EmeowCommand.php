<?php

namespace Emeow\Command;

use Emeow\EmailManager;
use Emeow\NetworkManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class EmeowCommand extends Command
{
    protected static $defaultName = 'emeow';

    private const ARG_RECIPIENT = 'recipient';
    private const OPT_SUBJECT = 'subject';
    private const OPT_NETWORK_INTERFACE = 'ifname';
    private const OPT_NETWORK_ADDRESS = 'ifaddr';
    private const OPT_TPL_VAR = 'var';
    private const OPT_TPL_FILE = 'tpl';
    private const OPT_HTML_EMAIL = 'html';
    private const OPT_MAILER_DSN = 'mailer-dsn';

    private $em;
    private $networkManager;

    public function __construct(?string $name = null, NetworkManager $networkManager, EmailManager $em)
    {
        $this->em = $em;
        $this->networkManager = $networkManager;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Emeow is a mailman who knows how to handle mail and network')
            ->addArgument(self::ARG_RECIPIENT, InputArgument::REQUIRED, 'Recipient address')
            ->addOption(self::OPT_SUBJECT, null, InputOption::VALUE_REQUIRED, 'Email Subject', 'You got Emeow!')
            ->addOption(self::OPT_MAILER_DSN, null, InputOption::VALUE_REQUIRED, 'E.g. gmail+stream://USERNAME:PASSWORD@default?ip=1.2.3.4')
            ->addOption(self::OPT_TPL_VAR, null, InputOption::VALUE_IS_ARRAY|InputOption::VALUE_REQUIRED, 'Variables to be used in email template in format KEY=VALUE')
            ->addOption(self::OPT_TPL_FILE, null, InputOption::VALUE_REQUIRED, 'Email template file', 'email.twig')
            ->addOption(self::OPT_HTML_EMAIL, null, InputOption::VALUE_NONE, 'Send email in html mode. Default is txt mode');
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($input->hasOption(self::OPT_TPL_FILE)) {
            $this->em->setTemplate($input->getOption(self::OPT_TPL_FILE));
        }

        $params = $this->optionsToKeyValues($input->getOption(self::OPT_TPL_VAR));
        $recipient = $input->getArgument(self::ARG_RECIPIENT);
        $subject = $input->getOption(self::OPT_SUBJECT);
        $dsn = $input->getOption(self::OPT_MAILER_DSN);

        $email = $this->em->render($recipient, $subject, $params);

        $this->em->send($dsn,$email);
        return 0;
    }

    private function optionsToKeyValues(array $options): array
    {
        if(empty($options)) {
            return [];
        }

        $map = [];
        foreach ($options as $opt) {
            $chunks = array_map('trim', explode('=', $opt));
            $map[$chunks[0]] = $chunks[1];
        }

        return $map;
    }
}