<?php


namespace App\ManageServices;


use App\Models\Gender;
use App\Repositories\GenderRepository;

class GenderService
{
    /**
     * @var GenderRepository
     */
    public $genderRepository;

    public function __construct(GenderRepository $genderRepository)
    {
        $this->genderRepository = $genderRepository;
    }

    public function create(string $name)
    {
        $model = Gender::createGender($name);
        $this->genderRepository->save($model);
        return $model;
    }
}
