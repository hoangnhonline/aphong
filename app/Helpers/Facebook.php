<?php

namespace App\Helpers;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;
/**
 * Class Facebook.
 */
class Facebook
{
    /**
     * Generate URL based on Video ID/Link.
     *
     * @param $url
     *
     * @return string
     */
     /**
     * @var Client
     */
    protected $client;

    /**
     * @var PromiseInterface[]
     */
    private static $promises = [];

    /**
     * @var string
     */
    protected $body;

    /**
     * VideoLinkGenerator constructor.
     *
     * @param Client|null $client
     */
     public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * Unwrap Promises.
     */
    public function __destruct()
    {
        Promise\unwrap(self::$promises);
    }
  
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Gets HTTP client for internal class use.
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Returns Raw Response Body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get Page Source Code.
     *
     * @param $url
     *
     * @throws VideoDownloaderException
     */
    public function getSourceCode($url)
    {
        $response = $this->httpRequest($url);

        $status = $response->getStatusCode();
        dd($response);
        if ($status === 200) {
            return $this->body = $response->getBody();
        }

        throw new VideoDownloaderException('Something went wrong, HTTP Status Code Returned: '.$status);
    }

    /**
     * Download remote file from server
     * and save it locally using HTTP Client.
     *
     * @param string $url            The URL to Remote File to Download.
     * @param string $dstFilename    Destination Filename (Accepts File Path too).
     * @param bool   $isAsyncRequest
     *
     * @return string
     */
    public function download($url, $dstFilename, $isAsyncRequest = false)
    {
        $baseDir = dirname($dstFilename);
        if (!is_writable($baseDir)) {
            @mkdir($baseDir, 0755, true);
        }

        $this->httpRequest($url, ['sink' => $dstFilename], $isAsyncRequest);

        return ['file_path' => $dstFilename];
    }

    /**
     * Make a HTTP Request.
     *
     * @param            $url
     * @param array      $options
     * @param bool|false $isAsyncRequest
     *
     * @return mixed
     */
    private function httpRequest($url, array $options = [], $isAsyncRequest = false)
    {
        if ($url == null || trim($url) == '') {
            return 'URL was invalid.';
        }

        $options = $this->getOptions($this->defaultHeaders(), $options, $isAsyncRequest);

        try {
            $response = $this->client->getAsync($url, $options);

            if ($isAsyncRequest) {
                self::$promises[] = $response;
            } else {
                $response = $response->wait();
            }
        } catch (RequestException $e) {
            return 'There was an error while processing the request';
        }

        return $response;
    }

    /**
     * Prepares and returns request options.
     *
     * @param array $headers
     * @param       $options
     * @param       $isAsyncRequest
     *
     * @return array
     */
    private function getOptions(array $headers, $options = [], $isAsyncRequest = false)
    {
        $default_options = [
            RequestOptions::HEADERS     => $headers,
            RequestOptions::SYNCHRONOUS => !$isAsyncRequest,
        ];

        return array_merge($default_options, $options);
    }

    /**
     * Returns Default Headers for HTTP Client.
     *
     * @return array
     */
    protected function defaultHeaders()
    {
        return [
            'User-Agent'      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.71 Safari/537.36',
            'Accept-Language' => 'en-US,en;q=0.8,sr;q=0.6,pt;q=0.4',
        ];
    }

    /**
     * Decode Unicode Sequences.
     *
     * @param $str
     *
     * @return mixed
     */
    protected function decodeUnicode($str)
    {
        return preg_replace_callback(
            '/\\\\u([0-9a-f]{4})/i',
            [$this, 'replace_unicode_escape_sequence'],
            $str
        );
    }

    /**
     * Cleanup string to readible text.
     *
     * @param string $str
     *
     * @return string
     */
    protected function cleanStr($str)
    {
        return html_entity_decode(strip_tags($str), ENT_QUOTES, 'UTF-8');
    }

    /**
     * @param $uni
     *
     * @return bool|mixed|string
     */
    protected function replace_unicode_escape_sequence($uni)
    {
        return mb_convert_encoding(pack('H*', $uni[1]), 'UTF-8', 'UCS-2BE');
    }
    public function generateUrl($url)
    {
        $url = 'https://www.facebook.com/nhahangamthuc02/videos/1657658481016263/';
        $id = '';
        if (is_int($url)) {
            $id = $url;
        } elseif (preg_match('/(?:\.?\d+)(?:\/videos)?\/?(\d+)?(?:[v]\=)?(\d+)?/i', $url, $matches)) {
            $id = $matches[1];
        }

        return 'https://www.facebook.com/video.php?v='.$id;
    }

    /**
     * Gets Video Download Links with Meta Data.
     * Returns HD & SD Quality Links.
     *
     * @param $url
     *
     * @throws VideoDownloaderException
     *
     * @return array
     */
    public function getVideoInfo($url)
    {
        $this->getSourceCode($this->generateUrl($url));

        $title = $this->getTitle();

        if (strtolower($title) === "sorry, this content isn't available at the moment") {
            throw new VideoDownloaderException('Video not available!');
        }

        $description = $this->getDescription();
        $owner = $this->getValueByKey('ownerName');
        $created_time = $this->getCreatedTime();
        var_dump($this->body);
        $hd_link = $this->getValueByKey('hd_src_no_ratelimit');
        $sd_link = $this->getValueByKey('sd_src_no_ratelimit');

        return compact('title', 'description', 'owner', 'created_time', 'hd_link', 'sd_link');
    }

    /**
     * Get Video Title.
     *
     * @return string|null
     */
    public function getTitle()
    {
        $title = null;
        if (preg_match('/h2 class="uiHeaderTitle"?[^>]+>(.+?)<\/h2>/', $this->body, $matches)) {
            $title = $matches[1];
        } elseif (preg_match('/title id="pageTitle">(.+?)<\/title>/', $this->body, $matches)) {
            $title = $matches[1];
        }

        return $this->cleanStr($title);
    }

    /**
     * Get Description.
     *
     * @return string|bool
     */
    public function getDescription()
    {
        if (preg_match('/span class="hasCaption">(.+?)<\/span>/', $this->body, $matches)) {
            return $this->cleanStr($matches[1]);
        }

        return false;
    }

    /**
     * Get Created Time in Unix.
     *
     * @return string
     */
    public function getCreatedTime()
    {
        if (preg_match('/data-utime="(.+?)"/', $this->body, $matches)) {
            return $matches[1];
        }

        return false;
    }

    /**
     * Get Value By Key Name.
     *
     * @param $key
     *
     * @return string|bool
     */
    public function getValueByKey($key)
    {
        if (preg_match('/"'.$key.'":"(.*?)"/i', $this->body, $matches)) {
            $str = $this->decodeUnicode($matches[1]);

            return stripslashes(rawurldecode($str));
        }

        return false;
    }
    
}
