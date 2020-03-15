<?php

namespace App\Console\Commands;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Parse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    {  //php artisan command:parse
        $storagePath = Storage::disk('local')->getAdapter()->getPathPrefix();
        /** @var \Box\Spout\Reader\XLSX\Reader $reader */
        $reader = ReaderEntityFactory::createReaderFromFile($storagePath . '/files/x6HwUYkIJfPXHAfOole7L8dtQBTWIOBplpNWmnpG_users3.xlsx');
        $reader->open($storagePath . '/files/x6HwUYkIJfPXHAfOole7L8dtQBTWIOBplpNWmnpG_users3.xlsx');

        $this->print_mem();
        $mem_usage = memory_get_usage();
        $it = $reader->getSheetIterator();
//        die();
        echo $mem_usage . "\n";
        echo (memory_get_usage() - $mem_usage) . "\n";
        $this->print_mem();
        /**
         * @var  $k
         * @var \Box\Spout\Reader\XLSX\Sheet $value
         */
        foreach ($it as $k => $value) {
//            $this->print_mem();
            $it2 = $value->getRowIterator();

            foreach ($it2 as $itk => $row) {
                $this->print_mem();
                $cells = $row->getCells();
                foreach ($cells as $ck => $cell) {
                    $cell;
                }
            }
        }
        $this->print_mem();
    }

    function print_mem()
    {
        /* Currently used memory */
        $mem_usage = memory_get_usage();

        /* Peak memory usage */
        $mem_peak = memory_get_peak_usage();
//
//        echo "The script is now using: <strong>" . round($mem_usage / 1024) . "KB</strong> of memory.\n";
//        echo "Peak usage: <strong>" . round($mem_peak / 1024) . "KB</strong> of memory.\n";
        echo round(($mem_usage / 1024)) . "-" . round(($mem_peak / 1024))."\n";
    }
}
