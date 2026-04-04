<?php

declare(strict_types=1);

namespace App\Traits;

trait HandlesMomoValidation
{
    /**
     * Returns the regex pattern matching valid prefixes for the selected network.
     */
    protected function getNetworkPrefixPattern(?string $network): string
    {
        return match ($network) {
            '13', 'MTN' => '/^0(24|54|55|59)\d{7}$/',  // MTN
            '6', 'Telecel' => '/^0(20|50)\d{7}$/',      // Telecel
            '7', 'AT' => '/^0(26|56|27|57)\d{7}$/',    // AT
            default => '/^0\d{9}$/',
        };
    }

    /**
     * Checks if the mobile money number is valid for the network.
     */
    protected function isValidMomoNumber(?string $network, ?string $number): bool
    {
        if (empty($network) || empty($number) || strlen($number) !== 10) {
            return false;
        }

        return (bool) preg_match($this->getNetworkPrefixPattern($network), $number);
    }

    /**
     * Get the placeholder hint based on the network.
     */
    protected function getMomoPlaceholder(?string $network): string
    {
        return match ($network) {
            '13', 'MTN' => '024 / 054 / 055 / 059',
            '6', 'Telecel' => '020 / 050',
            '7', 'AT' => '026 / 056 / 027 / 057',
            default => 'Select a network first',
        };
    }
}
