<?php

namespace App\Support;

use NumberFormatter;

class MoneyToWords
{
    public static function convert($amount, string $currency = 'USD', string $locale = 'en'): string
    {
        // Normalize amount (strip $, commas, spaces)
        if (is_string($amount)) {
            $amount = preg_replace('/[^\d.\-]/', '', $amount);
        }
        $amount = (float) $amount;

        $intPart  = (int) floor($amount);
        $fraction = (int) round(($amount - $intPart) * 100); // cents/paise

        // currency nouns
        [$majorSingular, $majorPlural, $minorSingular, $minorPlural] = self::currencyNouns($currency);

        // number to words (intl -> fallback)
        $intWords  = self::numToWords($intPart, $locale);
        $intNoun   = ($intPart == 1) ? $majorSingular : $majorPlural;

        $result = trim("$intWords $intNoun");

        if ($fraction > 0) {
            $fracWords = self::numToWords($fraction, $locale);
            $fracNoun  = ($fraction == 1) ? $minorSingular : $minorPlural;
            $result   .= " and $fracWords $fracNoun";
        }

        // Style (“ONLY” at end, uppercase like many invoices)
        return mb_strtoupper($result . ' only');
    }

    private static function currencyNouns(string $currency): array
    {
        $currency = strtoupper($currency);
        return match ($currency) {
            'INR' => ['rupee', 'rupees', 'paisa', 'paise'],
            'USD' => ['dollar', 'dollars', 'cent', 'cents'],
            'EUR' => ['euro', 'euros', 'cent', 'cents'],
            'GBP' => ['pound', 'pounds', 'pence', 'pence'],
            default => ['unit', 'units', 'subunit', 'subunits'],
        };
    }

    private static function numToWords(int $n, string $locale = 'en'): string
    {
        if ($n === 0) return 'zero';

        // Prefer Intl if available
        if (class_exists(NumberFormatter::class)) {
            $fmt = new NumberFormatter($locale, NumberFormatter::SPELLOUT);
            // Some locales produce dashes ("twenty-one"): normalize spacing
            $out = $fmt->format($n);
            return trim(preg_replace('/\s+/', ' ', str_replace('-', ' ', $out)));
        }

        // Fallback (en): supports up to trillions
        $ones  = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
                  'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen',
                  'sixteen', 'seventeen', 'eighteen', 'nineteen'];
        $tens  = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
        $units = ['', 'thousand', 'million', 'billion', 'trillion'];

        $words = [];
        $unitIdx = 0;

        while ($n > 0) {
            $chunk = $n % 1000;
            if ($chunk) {
                $chunkWords = [];

                $hundreds = intdiv($chunk, 100);
                $rem      = $chunk % 100;

                if ($hundreds) {
                    $chunkWords[] = $ones[$hundreds] . ' hundred';
                }

                if ($rem) {
                    if ($rem < 20) {
                        $chunkWords[] = $ones[$rem];
                    } else {
                        $chunkWords[] = trim($tens[intdiv($rem, 10)] . ' ' . $ones[$rem % 10]);
                    }
                }

                $unitWord = $units[$unitIdx] ?? '';
                $words[]  = trim(implode(' ', $chunkWords) . ' ' . $unitWord);
            }

            $n = intdiv($n, 1000);
            $unitIdx++;
        }

        return trim(implode(' ', array_reverse($words)));
    }
}
