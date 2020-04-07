<?php namespace Maisner\Tests;

class HomepageCest {

	// tests
	public function homepageWorks(AcceptanceTester $I): void {
		$I->amOnPage('/');
		$I->canSeeResponseCodeIs(200);
		$I->see('Congratulations!', 'H1');
	}

}
