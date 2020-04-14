<?php namespace Maisner\Tests;

use Codeception\Util\HttpCode;

class SalesPointsEndpointCest {

	public function withoutParameters(ApiTester $I): void {
		$I->sendGET('/sales-point');

		$I->seeResponseCodeIs(HttpCode::OK);
		$I->canSeeResponseIsJson();

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);
		$I->assertFalse($filterParams['only_open']);
		$I->assertSame($I->getCurrentDatetimeForTests(), $filterParams['datetime']);
		$I->assertSame('id_asc', $filterParams['sort_by']);
		$I->assertNull($filterParams['ip']);

		$this->assertSortering(
			$I,
			[
				1,
				2,
				3,
				4,
				5
			]
		);
	}

	public function sortByIdASC(ApiTester $I): void {
		$I->sendGET('/sales-point?sort_by=id_asc');

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);

		$I->assertSame('id_asc', $filterParams['sort_by']);

		$this->assertSortering(
			$I,
			[
				1,
				2,
				3,
				4,
				5
			]
		);
	}

	public function sortByIdDESC(ApiTester $I): void {
		$I->sendGET('/sales-point?sort_by=id_desc');

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);

		$I->assertSame('id_desc', $filterParams['sort_by']);

		$this->assertSortering(
			$I,
			[
				5,
				4,
				3,
				2,
				1
			]
		);
	}

	public function sortByDistanceAscIpClient(ApiTester $I): void {
		$I->sendGET('/sales-point?sort_by=distance_asc&ip=current');

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);

		$I->assertSame($I->getCurrentDatetimeForTests(), $filterParams['datetime']);
		$I->assertSame('distance_asc', $filterParams['sort_by']);
		$I->assertSame($I->getCurrentClientIpForTests(), $filterParams['ip']);

		$this->assertSortering(
			$I,
			[
				5,
				1,
				2,
				3,
				4
			]
		);
	}

	public function sortByDistanceDescIpClient(ApiTester $I): void {
		$I->sendGET('/sales-point?sort_by=distance_desc&ip=current');

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);

		$I->assertFalse($filterParams['only_open']);
		$I->assertSame($I->getCurrentDatetimeForTests(), $filterParams['datetime']);
		$I->assertSame('distance_desc', $filterParams['sort_by']);
		$I->assertSame($I->getCurrentClientIpForTests(), $filterParams['ip']);

		$this->assertSortering(
			$I,
			[
				4,
				3,
				2,
				1,
				5
			]
		);
	}

	public function sortByDistanceAscIpClientOnlyOpen(ApiTester $I): void {
		$I->sendGET('/sales-point?sort_by=distance_asc&ip=current&only_open=1');

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);

		$I->assertTrue($filterParams['only_open']);
		$I->assertSame($I->getCurrentDatetimeForTests(), $filterParams['datetime']);
		$I->assertSame('distance_asc', $filterParams['sort_by']);
		$I->assertSame($I->getCurrentClientIpForTests(), $filterParams['ip']);

		$this->assertSortering(
			$I,
			[
				1,
				2,
				3
			]
		);
	}

	public function sortByDistanceDescIpClientOnlyOpen(ApiTester $I): void {
		$I->sendGET('/sales-point?sort_by=distance_desc&ip=current&only_open=1');

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);

		$I->assertTrue($filterParams['only_open']);
		$I->assertSame($I->getCurrentDatetimeForTests(), $filterParams['datetime']);
		$I->assertSame('distance_desc', $filterParams['sort_by']);
		$I->assertSame($I->getCurrentClientIpForTests(), $filterParams['ip']);

		$this->assertSortering(
			$I,
			[
				3,
				2,
				1
			]
		);
	}

	public function sortByDistanceAscIpClientOnlyOpenByTimestamp(ApiTester $I): void {
		//at 7:45
		$I->sendGET('/sales-point?sort_by=distance_asc&ip=current&only_open=1&timestamp=1586843100');

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);

		$I->assertTrue($filterParams['only_open']);
		$I->assertSame('2020-04-14 07:45:00', $filterParams['datetime']);
		$I->assertSame('distance_asc', $filterParams['sort_by']);
		$I->assertSame($I->getCurrentClientIpForTests(), $filterParams['ip']);

		$this->assertSortering(
			$I,
			[]
		);

		//at 9:40
		$I->sendGET('/sales-point?sort_by=distance_asc&ip=current&only_open=1&timestamp=1586850000');

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);

		$I->assertTrue($filterParams['only_open']);
		$I->assertSame('2020-04-14 09:40:00', $filterParams['datetime']);
		$I->assertSame('distance_asc', $filterParams['sort_by']);
		$I->assertSame($I->getCurrentClientIpForTests(), $filterParams['ip']);

		$this->assertSortering(
			$I,
			[
				5,
				1
			]
		);
	}

	public function sortByDistanceAscIpFromParam(ApiTester $I): void {
		//IP Prague
		$I->sendGET('/sales-point?sort_by=distance_asc&ip=109.238.208.138');

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);

		$I->assertFalse($filterParams['only_open']);
		$I->assertSame($I->getCurrentDatetimeForTests(), $filterParams['datetime']);
		$I->assertSame('distance_asc', $filterParams['sort_by']);
		$I->assertSame('109.238.208.138', $filterParams['ip']);

		$this->assertSortering(
			$I,
			[
				3,
				2,
				4,
				1,
				5
			]
		);

		//IP Brno
		$I->sendGET('/sales-point?sort_by=distance_asc&ip=89.102.15.205');

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);

		$I->assertFalse($filterParams['only_open']);
		$I->assertSame($I->getCurrentDatetimeForTests(), $filterParams['datetime']);
		$I->assertSame('distance_asc', $filterParams['sort_by']);
		$I->assertSame('89.102.15.205', $filterParams['ip']);

		$this->assertSortering(
			$I,
			[
				1,
				2,
				5,
				3,
				4
			]
		);
	}

	public function sortByDistanceAscIpFromParamOnlyOpen(ApiTester $I): void {
		//IP Brno
		$I->sendGET('/sales-point?sort_by=distance_asc&ip=89.102.15.205&only_open=1');

		$filterParams = $this->getFilterParamsFromResponse($I);
		$I->assertIsArray($filterParams);

		$I->assertTrue($filterParams['only_open']);
		$I->assertSame($I->getCurrentDatetimeForTests(), $filterParams['datetime']);
		$I->assertSame('distance_asc', $filterParams['sort_by']);
		$I->assertSame('89.102.15.205', $filterParams['ip']);

		$this->assertSortering(
			$I,
			[
				1,
				2,
				3
			]
		);
	}

	protected function getFilterParamsFromResponse(ApiTester $I): ?array {
		return $I->grabDataFromResponseByJsonPath('$.filter_params')[0] ?? NULL;
	}

	protected function getSalesPointsDataFromResponse(ApiTester $I): ?array {
		return $I->grabDataFromResponseByJsonPath('$.data')[0] ?? NULL;
	}

	protected function assertSortering(ApiTester $I, array $ids): void {
		$salesPoints = $this->getSalesPointsDataFromResponse($I);
		$I->assertIsArray($salesPoints);
		$I->assertCount(count($ids), $salesPoints);

		foreach ($ids as $index => $id) {
			$I->assertSame($id, $salesPoints[$index]['id']);
		}
	}
}
