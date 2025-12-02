<?php

namespace App\Services;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class InvoiceNumberService
{
    /**
     * @param 'IN'|'US' $entity Two-letter entity code
     * @param Carbon|string|null $invoiceDate Use invoice date (or now)
     * @param string $goLiveFrom Apply this scheme from this date (inclusive)
     *
     * @throws \RuntimeException if date is before go-live and no fallback is desired
     */
    public static function next(string $entity, $invoiceDate = null, string $goLiveFrom = '2025-04-01'): string
    {
        $entity = strtoupper($entity) === 'US' ? 'US' : 'IN';

        $date = $invoiceDate ? Carbon::parse($invoiceDate) : now();
        [$fyStart, $fyEnd, $fyLabel] = self::fyFromDate($date);

        // Enforce go-live (start using new scheme only from FY starting 1 Apr 2025)
        $goLive = Carbon::parse($goLiveFrom);
        if ($fyStart->lt($goLive)) {
            // Option A: throw to force old numbering elsewhere
            throw new \RuntimeException('New invoice numbering applies from Apr 1, 2025.');
            // Option B: return some legacy format instead (uncomment if you have one)
            // return self::legacyFormat(...);
        }

        $prefix = $entity === 'US' ? 'SBS' : 'SB'; // US -> SBS, India -> SB

        // Find the last invoice in this FY & entity prefix and increment the tail.
        $pattern = $prefix . '-' . $fyLabel . '-%';

        $lastInvoiceNo = Invoice::query()
            ->whereBetween('invoice_date', [$fyStart->toDateString(), $fyEnd->toDateString()])
            ->where('invoice_no', 'like', $pattern)
            ->orderByDesc('id') // fast and good enough; adjust if you have a different surrogate
            ->value('invoice_no');

        $nextSeq = 1;
        if ($lastInvoiceNo && preg_match('/(\d{4})$/', $lastInvoiceNo, $m)) {
            $nextSeq = ((int) $m[1]) + 1;
        }

        $serial = str_pad((string) $nextSeq, 4, '0', STR_PAD_LEFT);
        return "{$prefix}-{$fyLabel}-{$serial}";
    }

    /**
     * Assigns the next invoice number to the given Invoice model safely (retry on race).
     *
     * Usage:
     *   $invoice = new Invoice([...]);
     *   InvoiceNumberService::assign($invoice, 'IN', $invoice->invoice_date);
     *   $invoice->save();
     */
    public static function assign(Invoice $invoice, string $entity, $invoiceDate = null): void
    {
        // Ensure you have a UNIQUE index on invoice_no to guarantee no duplicates.
        $retries = 3;
        do {
            $invoice->invoice_no = self::next($entity, $invoiceDate);
            try {
                // try to save in a transaction so related writes are atomic
                DB::transaction(function () use ($invoice) {
                    $invoice->save();
                });
                return; // success
            } catch (QueryException $e) {
                // On duplicate key, retry with a fresh number
                if (self::isDuplicateKey($e)) {
                    $retries--;
                    if ($retries <= 0) throw $e;
                } else {
                    throw $e;
                }
            }
        } while ($retries > 0);
    }

    /** Compute FY window + label for a date (Apr–Mar) → [start, end, "YY-YY"] */
    public static function fyFromDate(Carbon $date): array
    {
        $y = (int) $date->format('Y');
        $m = (int) $date->format('n');

        if ($m >= 4) { // Apr–Dec
            $start = Carbon::create($y, 4, 1)->startOfDay();
            $end   = Carbon::create($y + 1, 3, 31)->endOfDay();
            $label = substr((string)$y, -2) . '-' . substr((string)($y + 1), -2); // e.g., 25-26
        } else {       // Jan–Mar
            $start = Carbon::create($y - 1, 4, 1)->startOfDay();
            $end   = Carbon::create($y, 3, 31)->endOfDay();
            $label = substr((string)($y - 1), -2) . '-' . substr((string)$y, -2); // e.g., 24-25
        }
        return [$start, $end, $label];
    }

    private static function isDuplicateKey(QueryException $e): bool
    {
        // MySQL duplicate key error code = 1062
        return (int) ($e->errorInfo[1] ?? 0) === 1062;
    }
}
