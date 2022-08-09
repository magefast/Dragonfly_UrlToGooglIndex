<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Console\Command;

use Dragonfly\UrlToGooglIndex\Service\ReListUrlList;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Relist extends Command
{
    /**
     * @var ReListUrlList
     */
    private $reListUrlList;

    /**
     * @param ReListUrlList $reListUrlList
     * @param string|null $name
     */
    public function __construct(ReListUrlList $reListUrlList, string $name = null)
    {
        parent::__construct($name);
        $this->reListUrlList = $reListUrlList;
    }

    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('urltoindex:relist');
        $this->setDescription('Command for re-build, re-list List of Url for Processing to Google Index');
        parent::configure();
    }

    /**
     * CLI command description.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->reListUrlList->reload();
        // todo: implement CLI command logic here
    }
}
