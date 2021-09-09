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
        $loader = new ConsulLoader('services/dconf-demo');
        $this->injectMockedKV($loader);
        $config = $loader->load();
        $this->assertEquals('foo', $config['fer']);
    }

    public function testLoadWithMissingKeys(): void
    {
        $loader = new ConsulLoader('missing');
        $this->injectMockedKV($loader);
        $config = $loader->load();
        $this->assertEmpty($config);
    }

    public function testLoadWithError(): void
    {
        $this->expectException(ClientException::class);
        $loader = new ConsulLoader('error');
        $this->injectMockedKV($loader);
        $loader->load();
    }

    private function injectMockedKV(ConsulLoader $loader): void
    {
        $kv = $this->buildKV();
        $reflection = new \ReflectionObject($loader);
        $reflectionProperty = $reflection->getProperty('kv');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($loader, $kv);
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
