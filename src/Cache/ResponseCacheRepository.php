<?php

namespace ZEDx\Cache;

use Cache;

class ResponseCacheRepository
{
    /**
     * @var \ZEDx\Cache\ResponseSerializer
     */
    protected $responseSerializer;

    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \ZEDx\Cache\ResponseSerializer     $responseSerializer
     */
    public function __construct()
    {
        $this->responseSerializer = new ResponseSerializer();
    }

    /**
     * @param array                                      $hash
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \DateTime|int                              $minutes
     */
    public function put($hash, $response, $minutes)
    {
        $content = $this->responseSerializer->serialize($response);
        Cache::tags($hash['tags'])->put($hash['key'], $content, $minutes);
    }

    /**
     * @param array $hash
     *
     * @return bool
     */
    public function forever($hash, $response)
    {
        $content = $this->responseSerializer->serialize($response);
        Cache::tags($hash['tags'])->forever($hash['key'], $content);
    }

    /**
     * @param array $hash
     *
     * @return bool
     */
    public function has($hash)
    {
        return Cache::tags($hash['tags'])->has($hash['key']);
    }

    /**
     * @param array $hash
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function get($hash)
    {
        $content = Cache::tags($hash['tags'])->get($hash['key']);

        return $this->responseSerializer->unserialize($content);
    }

    public function flush()
    {
        Cache::flush();
    }
}
