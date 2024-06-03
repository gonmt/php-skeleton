<?php

declare(strict_types=1);

namespace CompanyName\Shared\Infrastructure\Bus\Query;

use CompanyName\Shared\Domain\Bus\Query\Query;
use RuntimeException;

final class QueryNotRegisteredError extends RuntimeException
{
	public function __construct(Query $query)
	{
		$queryClass = $query::class;

		parent::__construct("The query <$queryClass> has no associated query handler");
	}
}
