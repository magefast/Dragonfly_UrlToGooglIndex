<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dragonfly\UrlToGooglIndex\Service\SendToIndex as ServiceSendToIndex;

class SendToIndex extends Command
{
    /**
     * @var ServiceSendToIndex
     */
    private $serviceSendToIndex;

    /**
     * @param ServiceSendToIndex $serviceSendToIndex
     * @param string|null $name
     */
    public function __construct(ServiceSendToIndex $serviceSendToIndex, string $name = null)
    {
        parent::__construct($name);

        $this->serviceSendToIndex = $serviceSendToIndex;

    }

    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('urltoindex:googl');
        $this->setDescription('Command will execute function for Send URL to Google Indexing API');
        parent::configure();
    }

    /**
     * CLI command description.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->serviceSendToIndex->execute();
        // todo: implement CLI command logic here
    }
}
