<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    public $timestamps = false;

    public static $rules = [
        'name' => 'required|unique:country|max:255',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gender';

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
     * @return Gender
     */
    public static function createGender(string $name)
    {
        $model = new Gender();
        $model->name = $name;
        return $model;
    }
}
