<?php declare(strict_types = 1);

namespace Maisner\App\Model\SalesPoint;

use MyCLabs\Enum\Enum;

class Type extends Enum {

	public const TICKET_MACHINE = 'ticketMachine';

	public const TICKET_OFFICE_METRO = 'ticketOfficeMetro';

	public const INFORMATION_CENTER = 'informationCenter';

	public const TRAIN_STATION = 'trainStation';

	public const CARRIER_OFFICE = 'carrierOffice';

	public const CHIP_CARD_DISPENSE = 'chipCardDispense';

}
