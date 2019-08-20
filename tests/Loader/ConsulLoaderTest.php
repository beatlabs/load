<?php

declare(strict_types=1);

namespace BeatLabs\Tests\Loader;

use BeatLabs\Loader\ConsulLoader;
use PHPUnit\Framework\TestCase;
use SensioLabs\Consul\ConsulResponse;
use SensioLabs\Consul\Exception\ClientException;
use SensioLabs\Consul\Services\KVInterface;

class ConsulLoaderTest extends TestCase
{
    public function testConstruct(): void
    {
        $loader = new ConsulLoader();
        $this->assertInstanceOf(ConsulLoader::class, $loader);
    }

    public function testLoad(): void
    {
        $kv = $this->buildKV();
        $loader = new ConsulLoader('services/dconf-demo', $kv);
        $config = $loader->load();
        $this->assertEquals('foo', $config['fer']);
    }

    public function testLoadWithMissingKeys(): void
    {
        $kv = $this->buildKV();
        $loader = new ConsulLoader('missing', $kv);
        $config = $loader->load();
        $this->assertEmpty($config);
    }

    public function testLoadWithError(): void
    {
        $this->expectException(ClientException::class);
        $kv = $this->buildKV();
        $loader = new ConsulLoader('error', $kv);
        $config = $loader->load();
    }

    private function buildKV(): KVInterface
    {
        $kv = $this->getMockBuilder(KVInterface::class)
            ->setMethods([
                'get',
                'put',
                'delete'
            ])
            ->getMock();

        $kv->expects($this->any())
            ->method('get')
            ->willReturnCallback(function (string $key = '', array $options = []) {
                if ($key === 'missing') {
                    throw new ClientException("Missing key", 404);
                } elseif ($key === 'error') {
                    throw new ClientException("Error fetching keys", 500);
                } else {
                    $data = [
                        'services/dconf-demo/fer' => 'foo',
                        'services/dconf-demo/foo/bar' => 'baz',
                        'services/dconf-demo/foo/barr' => 'bazz',
                        'services/dconf-demo/fooo/barr/bazz/boo' => 'hoo'
                    ];
                }
                if (isset($options['keys'])) {
                    $body = json_encode(array_keys($data));
                } else {
                    $body = $data[$key] ?? null;
                }

                return new ConsulResponse([], $body);
            });

        return $kv;
    }
}
