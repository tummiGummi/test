<?php


namespace App\Repositories;


use App\Exceptions\ModelSaveException;
use App\Models\Gender;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;

class GenderRepository
{
    public $db;

    public function __construct(DatabaseManager $database)
    {
        $this->db = $database;
    }

    /**
     * @param Gender $model
     * @throws ModelSaveException
     */
    public function save(Gender $model)
    {
        if (!$model->save()) {
            throw new ModelSaveException('User saving error.');
        }
    }

    public function findByName(string $name)
    {
        $query = $this->db->connection()->table('gender')
            ->where('name', '=', $name);
        return $query->first();;
    }
}
