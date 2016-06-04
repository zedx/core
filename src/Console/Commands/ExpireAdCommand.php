<?php

namespace ZEDx\Console\Commands;

use Carbon\Carbon;
use ZEDx\Models\Ad;
use ZEDx\Models\Adstatus;
use ZEDx\Events\Ad\AdWasExpired;
use Illuminate\Console\Command;

class ExpireAdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ad:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check expired Ad and change their status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ads = $this->getExpiredAds();
        $expiredStatus = Adstatus::whereTitle('expired')->firstOrFail();
        Ad::whereIn('id', $ads->lists('id')->toArray())
            ->update(['adstatus_id' => $expiredStatus->id]);

        foreach ($ads as $ad) {
            event(
                new AdWasExpired($ad, 'ZEDx')
            );
        }
    }

    protected function getExpiredAds()
    {
        $status = Adstatus::whereTitle('validate')->firstOrFail();
        $ads = $status->ads()
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', Carbon::now()->toDateTimeString())
            ->get();

        return $ads;
    }
}
