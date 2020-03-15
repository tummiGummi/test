<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public static $rules = [
        'name' => 'required|unique:country|max:255',
    ];

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'country';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @param string $name
     * @return Country
     */
    public static function createCountry(string $name)
    {
        $model = new Country();
        $model->name = $name;
        return $model;
    }
}
