<?php namespace Maisner\Tests;

class SalesPointsEndpointCest {

	public function withoutParameters(ApiTester $I): void {
		$I->sendGET('/sales-point');

		$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
	}
}
