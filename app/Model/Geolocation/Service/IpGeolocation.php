<?php declare(strict_types = 1);

namespace Maisner\App\Model\Geolocation\Service;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Maisner\App\Model\Exception\HttpException;
use Maisner\App\Model\Geolocation\GeolocationServiceInterface;
use Maisner\App\Model\Utils\GpsCoordinates;
use Maisner\App\Model\Utils\IP;
use Nette\SmartObject;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Tracy\ILogger;

/**
 * Geolocation service for https://ipgeolocation.io/
 *
 * Class IpGeolocation
 * @package Maisner\App\Model\Geolocation\Service
 */
class IpGeolocation implements GeolocationServiceInterface {

	use SmartObject;

	private string $apiKey;

	private ClientInterface $httpClient;

	private ILogger $logger;

	public function __construct(string $apiKey, ClientInterface $httpClient, ILogger $logger) {
		$this->apiKey = $apiKey;
		$this->httpClient = $httpClient;
		$this->logger = $logger;
	}

	/**
	 * @param IP $ip
	 * @return GpsCoordinates
	 * @throws HttpException
	 */
	public function getGpsByIp(IP $ip): GpsCoordinates {
		try {
			$response = $this->httpClient->request('GET', $this->buildRequestUrl($ip));
		} catch (GuzzleException $e) {
			$this->logger->log($e, 'HTTP_COMMUNICATION');

			throw new HttpException((string)$e);
		}

		if ($response->getStatusCode() !== 200) {
			throw new HttpException(
				\sprintf('Response code must be 200. Actual response code is %s', $response->getStatusCode())
			);
		}

		try {
			$data = Json::decode((string)$response->getBody(), Json::FORCE_ARRAY);
		} catch (JsonException $e) {
			throw new HttpException(\sprintf('Response data is invalid. %s', (string)$e));
		}

		if (!isset($data['latitude'], $data['longitude'])) {
			throw new HttpException('Response data is invalid. Not contain longitude or latitude');
		}

		return new GpsCoordinates((float)$data['latitude'], (float)$data['longitude']);
	}

	protected function buildRequestUrl(IP $ip): string {
		return \sprintf('https://api.ipgeolocation.io/ipgeo?apiKey=%s&ip=%s', $this->apiKey, $ip->getValue());
	}
}
