<?php

namespace App\Repositories;

use Illuminate\Pagination\Paginator;
use App\Models\User;

class UserRepository
{
    public function paginateUsers()
    {
        return User::paginate(5);
    }

    public function paginateUsersByKeyword($keyword)
    {
        return User::KeywordSearch($keyword)->paginate(5);
    }

    public function findUser($id)
    {
        return User::find($id);
    }
}