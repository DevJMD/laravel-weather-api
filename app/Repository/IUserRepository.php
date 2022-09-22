<?php

namespace App\Repository;

use App\Models\User;

interface IUserRepository
{
	/**
	 * Get a user by their email address.
	 *
	 * @param int $id
	 *
	 * @return User|null
	 */
	public function get (int $id) : User | null;
}
