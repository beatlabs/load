<?php

declare(strict_types=1);

namespace BeatLabs\Load\Loader;

use BeatLabs\Load\Interfaces\Loader;
use SensioLabs\Consul\Exception\ClientException;
use SensioLabs\Consul\ServiceFactory;
use SensioLabs\Consul\Services\KVInterface;

class ConsulLoader implements Loader
{
    const CONSUL_KEY_SEPARATOR = '/';

    /** @var string */
    private $root;

    /** @var KVInterface */
    private $kv;

    public function __construct(string $root = '', array $options = [])
    {
        $this->root = $root;
        $sf = new ServiceFactory($options);
        $this->kv = $sf->get(KVInterface::class);
    }

    /**
     * Load configuration
     *
     * @return array
     */
    public function load(): array
    {
        $keys = $this->getAvailableKeys();
        $values = [];
        foreach ($keys as $key) {
            $valueResponse = $this->kv->get($key, ['raw' => true]);
            $values[$key] = $valueResponse->getBody();
        }

        return $this->expandKV($values);
    }

    /**
     * Expand Consul key/value structure to associative array
     *
     * @param array $values
     *
     * @return array
     */
    private function expandKV(array $values): array
    {
        //Strip root from keys
        $rootLength = strlen($this->root);
        if ($rootLength > 0) {
            $rootLength++;
        }
        $strippedValues = [];
        foreach ($values as $key => $value) {
            $strippedValues[substr($key, $rootLength)] = $value;
        }

        //Expand to array
        $array = [];
        foreach ($strippedValues as $key => $value) {
            $keyParts = explode(self::CONSUL_KEY_SEPARATOR, $key);
            $pointer = &$array;
            foreach ($keyParts as $keyPart) {
                if (!isset($pointer[$keyPart])) {
                    $pointer[$keyPart] = null;
                }
                $pointer = &$pointer[$keyPart];
            }
            $pointer = $value;
        }

        return $array;
    }
    /**
     * Returns available keys from Consul
     *
     * @return array
     */
    private function getAvailableKeys(): array
    {
        try {
            $response = $this->kv->get($this->root, ['keys' => true]);
            $keyList = json_decode($response->getBody(), true);
        } catch (ClientException $ex) {
            // If root key is missing return empty list, else rethrow exception
            if ($ex->getCode() === 404) {
                return [];
            }
            throw $ex;
        }

        return $keyList;
    }
}
