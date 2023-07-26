<?php

namespace App\Tests\Unit\Service;

use App\Enum\HealthStatus;
use App\Service\GitHubService;
use App\Tests\Traits\Service\ServiceDataProviderTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GitHubServiceTest extends TestCase
{

    use ServiceDataProviderTrait;

    /**
     * @param HealthStatus $expectedStatus
     * @param string $dinoName
     * @dataProvider dinoNameProvider
     * @return void
     */
    public function testGetHealthReportReturnsCorrectHealthStatusForDino(HealthStatus $expectedStatus, string $dinoName): void
    {
        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockResponse = $this->createMock(ResponseInterface::class);

        $mockResponse
            ->method('toArray')
            ->willReturn([
                [
                    'title' => 'Daisy',
                    'labels' => [['name' => 'Status: Sick']],
                ],
                [
                    'title' => 'Maverick',
                    'labels' => [['name' => 'Status: Healthy']],
                ],
            ]);

        $mockHttpClient
            ->expects(self::once())
            ->method('request')
            ->with('GET', 'https://api.github.com/repos/SymfonyCasts/dino-park/issues')
            ->willReturn($mockResponse);

        $service = new GithubService($mockHttpClient, $mockLogger);
        try {
            self::assertSame($expectedStatus, $service->getHealthReport($dinoName));
        } catch (ClientExceptionInterface $e) {
        } catch (DecodingExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
    }

    /**
     * @param HealthStatus $expectedStatus
     * @param string $dinoName
     * @dataProvider dinoNameProvider
     * @return void
     */
    public function testExceptionThrownWithUnknownLabel(HealthStatus $expectedStatus, string $dinoName): void
    {
        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockResponse = $this->createMock(ResponseInterface::class);

        $mockResponse
            ->method('toArray')
            ->willReturn([
                [
                    'title' => 'Maverick',
                    'labels' => [['name' => 'Status: Drowsy']],
                ],
            ]);

        $mockHttpClient
            ->expects(self::once())
            ->method('request')
            ->with('GET', 'https://api.github.com/repos/SymfonyCasts/dino-park/issues')
            ->willReturn($mockResponse);

        $service = new GithubService($mockHttpClient, $mockLogger);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Drowsy is an unknown status label!');

        try {
            self::assertSame($expectedStatus, $service->getHealthReport('Maverick'));
        } catch (ClientExceptionInterface $e) {
        } catch (DecodingExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }

    }


}