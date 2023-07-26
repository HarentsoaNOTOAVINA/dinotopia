<?php

namespace App\Service;

use App\Enum\HealthStatus;
use JetBrains\PhpStorm\NoReturn;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubService
{

    /**
     * @param LoggerInterface $logger
     * @param HttpClientInterface $httpClient
     */
    public function __construct(private readonly HttpClientInterface $httpClient, private readonly LoggerInterface $logger)
    {

    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getHealthReport(string $dinosaurName): HealthStatus
    {
        $health = HealthStatus::HEALTHY;


        $response = $this->httpClient->request(
            method: 'GET',
            url: 'https://api.github.com/repos/SymfonyCasts/dino-park/issues'
        );

        $this->logger->info('Request Dino Issues', [
            'dino' => $dinosaurName,
            'responseStatus' => $response->getStatusCode(),
        ]);


        foreach ($response->toArray() as $issue){
            if (str_contains($issue['title'], $dinosaurName)) {
                $health = $this->getDinoStatusFromLabels($issue['labels']);
            }
        }

        return $health;

    }

    public function getDinoStatusFromLabels(array $labels): HealthStatus
    {
        $status = null;
        $health = null;

        foreach ($labels as $label) {
            $label = $label['name'];

            if(!str_starts_with($label, 'Status:')){
                continue;
            }

            // Remove the "Status:" and whitespace from the label
            $status = trim(substr($label, strlen('Status:')));
            $health = HealthStatus::tryFrom($status);

            if(null === $health){
                throw new \RuntimeException(sprintf('%s is an unknown status label', $label));
            }
        }

        return $health ?? HealthStatus::HEALTHY;
    }
}