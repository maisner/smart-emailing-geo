<?php namespace Maisner\Tests;

class HomepageCest {

	// tests
	public function homepageWorks(AcceptanceTester $I): void {
		$I->amOnPage('/');
		$I->see('Congratulations!', 'H1');
	}

}
