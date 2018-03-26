<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Customer extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'customers';

	 /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id',
      'fullname',
      'email',
      'username',
      'password',
      'type',
      'last_login',
      'status',     
      'facebook_id',
      'image_url',
      'key_reset',
      'gender',
      'phone',
      'valid_from',
      'valid_to'
    ];
    
    public function links()
    {
        return $this->hasMany('App\Models\DataVideo', 'customer_id');
    }
}
