<?php


namespace App\ManageServices;


use App\Models\Country;
use App\Models\Gender;
use App\Repositories\CountryRepository;

class CountryService
{
    /**
     * @var CountryRepository
     */
    public $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }
    public function create(string $name)
    {
        $model = Country::createCountry($name);
        $this->countryRepository->save($model);
        return $model;
    }
}
