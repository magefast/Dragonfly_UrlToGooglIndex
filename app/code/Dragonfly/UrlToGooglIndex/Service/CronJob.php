<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

namespace Dragonfly\UrlToGooglIndex\Service;

use Dragonfly\UrlToGooglIndex\Service\SendToIndex as ServiceSendToIndex;
use Magento\Framework\Exception\LocalizedException;

class CronJob
{
    /**
     * @var SendToIndex
     */
    private $serviceSendToIndex;

    /**
     * @var ReListUrlList
     */
    private $reListUrlList;

    /**
     * @param SendToIndex $serviceSendToIndex
     * @param ReListUrlList $reListUrlList
     */
    public function __construct(ServiceSendToIndex $serviceSendToIndex, ReListUrlList $reListUrlList)
    {
        $this->serviceSendToIndex = $serviceSendToIndex;
        $this->reListUrlList = $reListUrlList;
    }

    /**
     * @throws LocalizedException
     */
    public function execute()
    {
        $this->reListUrlList->reload();
        sleep(3);
        $this->serviceSendToIndex->execute();
    }
}