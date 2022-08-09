<?php
/**
 * @author magefast@gmail.com www.magefast.com
 */

declare(strict_types=1);

#
# https://developers.google.com/search/apis/indexing-api/v3/core-errors?hl=en
#

namespace Dragonfly\UrlToGooglIndex\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;

class GoogleApi
{
    public const API_ENDPOINT = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
    public const AUTH_FILE_PATH = 'service/your_auth_file_for_google_api.json';

    private $googleClient;

    /**
     * @var ClientInterface
     */
    private $httpClientGoogle = null;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var File
     */
    private $fileDriver;

    /**
     * @param DirectoryList $directoryList
     * @param File $fileDriver
     */
    public function __construct(
        DirectoryList $directoryList,
        File          $fileDriver
    )
    {
        $this->directoryList = $directoryList;
        $this->fileDriver = $fileDriver;

        $this->getGoogleClient();
    }

    /**
     * @param string $url
     * @return bool
     * @throws FileSystemException
     * @throws LocalizedException
     * @throws Exception
     * @throws GuzzleException
     */
    public function add(string $url): bool
    {
        if ($this->httpClientGoogle === null) {

            // service_account_file.json is the private key that you created for your service account.
            $authFile = $this->getAuthFile();
            $this->googleClient->setAuthConfig($authFile);
            $this->googleClient->addScope('https://www.googleapis.com/auth/indexing');

            // Get a Guzzle HTTP Client
            $this->httpClientGoogle = $this->googleClient->authorize();
        }

        $content = '{"url": "' . $url . '","type": "URL_UPDATED"}';
        $response = $this->httpClientGoogle->post(self::API_ENDPOINT, ['body' => $content]);
        $status_code = $response->getStatusCode();

        if ($status_code === 200) {
            $this->log($status_code . " $url");
            return true;
        }

        $this->log($status_code . " $url");
        throw new LocalizedException(__('ERROR Code: %1', $status_code));
    }

    /**
     * @throws FileSystemException
     * @throws LocalizedException
     */
    private function getAuthFile(): string
    {
        $dirRoot = $this->directoryList->getRoot();

        $filePath = $dirRoot . '/' . self::AUTH_FILE_PATH;

        if ($this->fileDriver->isExists($filePath)) {
            return $filePath;
        }

        throw new LocalizedException(__('Not Exist Auth file for Google API.'));
    }

    /**
     * @param $value
     */
    private function log($value)
    {
        $dir = $this->directoryList->getRoot();
        $writer = new \Zend_Log_Writer_Stream($dir . '/var/log/googl_indexing_api.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info($value);
    }

    private function getGoogleClient()
    {
        $dir = $this->directoryList->getRoot();
        require_once $dir . '/service/google-api-php-client-main/vendor/autoload.php';

        $this->googleClient = new \Google\Client();
    }
}