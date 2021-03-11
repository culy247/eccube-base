<?php

namespace Customize\Mercure;

use Lcobucci\JWT\ClaimsFormatter;
use Lcobucci\JWT\Token\RegisteredClaims;

class UnixTimestampDateConversion implements ClaimsFormatter
{
    public function formatClaims(array $claims): array
    {
        foreach (RegisteredClaims::DATE_CLAIMS as $claim) {
            if (!array_key_exists($claim, $claims)) {
                continue;
            }
            $claims[$claim] = $this->convertDate($claims[$claim]);
        }

        return $claims;
    }

    private function convertDate(\DateTimeImmutable $date): int
    {
        return $date->getTimestamp();
    }
}
