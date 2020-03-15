<?php


namespace App\Repositories;


use App\Exceptions\ModelSaveException;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public $db;

    public function __construct(DatabaseManager $database)
    {
        $this->db = $database;
    }

    /**
     * @param User $model
     * @throws ModelSaveException
     */
    public function save(User $model)
    {
        if (!$model->save()) {
            throw new ModelSaveException('User saving error.');
        }
    }

    /**
     * @param $data
     * @throws ModelSaveException
     */
    public function insertMultiple($data)
    {
        if (!$this->db->table('user')->insert($data)) {
            throw new ModelSaveException('Users saving error.');
        }
    }

    /**
     * @return int
     */
    public function getUsersCount()
    {
        $query = $this->db->table('user');
        return $query->count();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getUsersCountByGender()
    {
        $query = $this->db->table('user');
        $query->selectRaw('count(*) as cnt, user.gender_id,  gender.name');
        $query->join('gender', 'user.gender_id', '=', 'gender.id');
        $query->groupBy('user.gender_id');
        return $query->get();
    }

    /**
     * @return mixed
     */
    public function getAverageAge()
    {
        $query = $this->db->table('user');
        return $query->avg('age');
    }


    /**
     * @return mixed
     */
    public function getAverageAgeByGender()
    {
        $query = $this->db->table('user');
        $query->selectRaw('AVG(age) as avgAge, gender.name');
        $query->join('gender', 'user.gender_id', '=', 'gender.id');
        $query->groupBy('user.gender_id');
        return $query->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getUsersCountByCountry()
    {
        $query = $this->db->table('user');
        $query->selectRaw('count(*) as cnt, country.name');
        $query->join('country', 'user.country_id', '=', 'country.id');
        $query->groupBy('user.country_id');
        return $query->get();
    }

}
