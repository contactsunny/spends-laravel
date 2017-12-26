<?php

namespace App\Core;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class UuidGenerator {

	public static function getNewUuid() {

		try {
            $uuid5 = Uuid::uuid5(Uuid::NAMESPACE_DNS, strtotime('now'));
            return $uuid5->toString();
        } catch(UnsatisfiedDependencyException $e) {
        	\Log::info('UUID Error: ' . $e->getMessage());
            return null;
        }
	}
}