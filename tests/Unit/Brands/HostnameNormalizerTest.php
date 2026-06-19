<?php

namespace Tests\Unit\Brands;

use App\Services\Brands\HostnameNormalizer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class HostnameNormalizerTest extends TestCase
{
    public function test_it_normalizes_case_port_and_trailing_dot(): void
    {
        $normalizer = new HostnameNormalizer();

        $this->assertSame(
            'maacdurgapur.local',
            $normalizer->normalize(' MAACDURGAPUR.LOCAL:80 ')
        );
        $this->assertSame(
            'maacdurgapur.local',
            $normalizer->normalize('maacdurgapur.local.')
        );
    }

    /**
     * @dataProvider invalidHostnameProvider
     */
    public function test_it_rejects_invalid_hostnames(string $hostname): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new HostnameNormalizer())->normalize($hostname);
    }

    public function invalidHostnameProvider(): array
    {
        return [
            'empty' => [''],
            'path' => ['maacdurgapur.local/path'],
            'query' => ['maacdurgapur.local?brand=aksha'],
            'userinfo' => ['user@maacdurgapur.local'],
            'invalid port' => ['maacdurgapur.local:http'],
            'underscore' => ['maac_durgapur.local'],
            'leading hyphen' => ['-maac.local'],
            'ip address' => ['127.0.0.1'],
        ];
    }
}
