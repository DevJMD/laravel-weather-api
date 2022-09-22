<?php

namespace App\Repository;

use App\Models\User;

class UserRepository implements IUserRepository
{
	/**
	 * Fetch user by ID.
	 *
	 * @param int $id
	 *
	 * @return User|null
	 */
	public function get (int $id) : User | null
	{
		return User::find($id)->first();
	}
}
