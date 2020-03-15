<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;

    public static $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'age' => 'required|integer|max:255|min:0',
        'country_id' => 'required',
        'gender_id' => 'required',
        'created_at' => 'required|date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'country_id', 'gender_id', 'age', 'created_at'
    ];

    public function country()
    {
        return $this->hasOne(Country::class, 'country_id', 'id');
    }


    public function gender()
    {
        return $this->hasOne(Gender::class, 'gender_id', 'id');
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param integer $gender
     * @param integer $country
     * @param string $createdAt
     * @return User
     */
    public static function createUser(string $firstName, string $lastName, integer $gender, integer $country,
                                      string $createdAt)
    {
        $model = new User();
        $model->first_name = $firstName;
        $model->last_name = $lastName;
        $model->gender_id = $gender;
        $model->country_id = $country;
        $model->created_at = $createdAt;
        return $model;
    }

}
