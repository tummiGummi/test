<?php

namespace App\Jobs;

use App\Exceptions\ModelSaveException;
use App\ManageServices\CountryService;
use App\ManageServices\GenderService;
use App\Models\Country;
use App\Models\Gender;
use App\Models\User;
use App\Repositories\CountryRepository;
use App\Repositories\GenderRepository;
use App\Repositories\UserRepository;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ParseFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Name of the file
     * @var string
     */
    public $fileName;

    /**
     * If true parser should skip first row in the file
     * @var boolean
     */
    public $containsHeader;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileName, $containsHeader = false)
    {
        $this->fileName = $fileName;
        $this->containsHeader = $containsHeader;
    }

    public function getFilePath()
    {
        return storage_path('app/files/' . $this->fileName);
    }

    public function removeFile()
    {
        unlink($this->getFilePath());
    }

    /**
     * @param array $values
     * @param array $rules
     * @throws ValidationException
     */
    private function validate($values, $rules)
    {
        $validator = Validator::make($values, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CountryRepository $countryRepository, GenderRepository $genderRepository,
                           CountryService $countryService, GenderService $genderService,
                           UserRepository $userRepository)
    {
        $file = $this->getFilePath();
        $reader = ReaderEntityFactory::createReaderFromFile($file);
        $reader->open($file);
        $sheetIterator = $reader->getSheetIterator();

        $limit = 100;
        $processed = 0;
        $processedWithoutErrors = true;
        $users = [];
        $rowsNum = [];
        foreach ($sheetIterator as $i => $sheet) {
            $rowIterator = $sheet->getRowIterator();
            $rowIterator->rewind();
            if ($this->containsHeader && $rowIterator->key() == 1) {
                $rowIterator->next();
            }
            while ($rowIterator->valid()) {
                $cells = $rowIterator->current()->toArray();
                try {
                    foreach ($cells as $ck => $cell) {
                        switch ($ck) {
                            case 0:
                                $userData['first_name'] = $cell;
                                break;
                            case 1:
                                $userData['last_name'] = $cell;
                                break;
                            case 2:
                                $gender = $genderRepository->findByName($cell);
                                if (empty($gender)) {
                                    $this->validate(['name' => $cell], Gender::$rules);
                                    $gender = $genderService->create($cell);
                                }
                                if (!empty($gender)) {
                                    $userData['gender_id'] = $gender->id;
                                }
                                break;
                            case 3:
                                $country = $countryRepository->findByName($cell);
                                if (empty($country)) {
                                    $this->validate(['name' => $cell], Country::$rules);
                                    $country = $countryService->create($cell);
                                }
                                if (!empty($country)) {
                                    $userData['country_id'] = $country->id;
                                }

                                break;
                            case 4:
                                $userData['age'] = (integer)$cell;
                                break;
                            case 5:
                                try {
                                    $userData['created_at'] = Carbon::createFromFormat('d/m/Y', $cell)->format('Y-m-d');;
                                } catch (\Exception $exception) {
                                    $userData['created_at'] = $cell;
                                }
                                break;
                        }

                    }

                    $this->validate($userData, User::$rules);
                    $users[] = $userData;
                    $rowsNum[] = $rowIterator->key();

                } catch (ValidationException $exception) {
                    Log::channel('parser')->warning($exception->getMessage(), [
                        'file' => $file,
                        'row' => $rowIterator->key(),
                        'errors' => $exception->errors(),
                    ]);
                    $processedWithoutErrors = false;
                }
                $rowIterator->next();
                $processed++;
                if ($processed == $limit || ($processed <= $limit && !$rowIterator->valid())) {
                    $processed = 0;
                    if (!empty($users)) {
                        try{
                            $userRepository->insertMultiple($users);
                        }catch (ModelSaveException $exception){
                            $processedWithoutErrors = false;
                            Log::channel('parser')->warning($exception->getMessage(), [
                                'file' => $file,
                                'rows' => $rowsNum,
                            ]);
                        }
                    }
                    $users = [];
                    $rowsNum = [];
                }
            }

        }

        if($processedWithoutErrors){
            $this->removeFile();
        }else{
            //Notify admin by email or into slack, but for test logging to file
            Log::channel('parser')->warning("File parsing finished with errors.", [
                'file' => $file,
            ]);
        }
    }
}
