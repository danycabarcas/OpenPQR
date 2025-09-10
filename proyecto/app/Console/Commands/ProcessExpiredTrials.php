<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
use App\Models\Plan;

class ProcessExpiredTrials extends Command
{
    protected $signature = 'openpqr:process-expired-trials';
    protected $description = 'Convierte empresas a STARTUP cuando vence el trial de Essential';

    public function handle()
    {
        $today = Carbon::today();
        $startup = Plan::where('slug', 'startup')->first();

        if (! $startup) {
            $this->error('El plan STARTUP no existe.');
            return self::FAILURE;
        }

        $subs = Subscription::query()
            ->where('is_trial', true)
            ->where('status', 'active')
            ->whereDate('end_date', '<', $today)
            ->get();

        $count = 0;

        DB::transaction(function () use ($subs, $startup, $today, &$count) {
            foreach ($subs as $s) {
                $s->update(['status' => 'expired']);

                Subscription::create([
                    'company_id'  => $s->company_id,
                    'plan_id'     => $startup->id,
                    'is_trial'    => false,
                    'status'      => 'active',
                    'start_date'  => $today->toDateString(),
                    'end_date'    => null,
                    'price'       => $startup->price ?? 0,
                ]);

                $count++;
            }
        });

        $this->info("Procesadas {$count} suscripciones de prueba vencidas.");
        return self::SUCCESS;
    }
}
