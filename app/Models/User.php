<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @method static where( string $string, string $email )
 * @method static create( array $array )
 * @method static find( int $id )
 * @method static whereIn( string $string, array $array )
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

	/**
	 * @implements \Tymon\JWTAuth\Contracts\JWTSubject
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * @implements \Tymon\JWTAuth\Contracts\JWTSubject
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}
}
