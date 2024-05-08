<?php

namespace App\Repositories;

use Illuminate\Pagination\Paginator;
use App\Models\User;

class UserRepository
{
    //全てのユーザー情報のページネーションインスタンスを返す
    public function paginateUsers()
    {
        return User::paginate(5);
    }

    //検索キーワードで検索し、該当のユーザー情報のページネーションインスタンスを返す
    public function paginateUsersByKeyword($keyword)
    {
        return User::KeywordSearch($keyword)->paginate(5);
    }

    //特定のユーザー情報を取得
    public function findUser($user_id)
    {
        return User::find($user_id);
    }
}