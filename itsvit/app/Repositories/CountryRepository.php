<?php


namespace App\Repositories;


use App\Exceptions\ModelSaveException;
use App\Models\Country;
use Illuminate\Database\DatabaseManager;

class CountryRepository
{
    public $db;

    public function __construct(DatabaseManager $database)
    {
        $this->db = $database;
    }

    /**
     * @param Country $model
     * @throws ModelSaveException
     */
    public function save(Country $model)
    {
        if (!$model->save()) {
            throw new ModelSaveException('User saving error.');
        }
    }


    public function findByName(string $name)
    {
        $query = $this->db->connection()->table('country')
            ->where('name', '=', $name);
        return $query->first();;
    }
}
