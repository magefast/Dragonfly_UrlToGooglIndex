<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Cron;

use Dragonfly\UrlToGooglIndex\Service\CronJob;
use Magento\Framework\Exception\LocalizedException;

class AddUrlToGooglIndex
{
    /**
     * @var CronJob
     */
    private $cronJob;

    /**
     * @param CronJob $cronJob
     */
    public function __construct(CronJob $cronJob)
    {
        $this->cronJob = $cronJob;
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function execute(): void
    {
        $this->cronJob->execute();
        // todo: implement cronjob logic here
    }
}
