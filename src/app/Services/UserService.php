<?php
namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    //全てのユーザー情報を取得
    public function getUsers()
    {
        return $this->userRepository->paginateUsers();
    }

    //検索キーワードで検索し、該当するユーザー情報を取得
    public function searchUsers($keyword)
    {
        return $this->userRepository->paginateUsersByKeyword($keyword);
    }

    //特定のユーザー情報を取得
    public function getUser($user_id)
    {
        return $this->userRepository->findUser($user_id);
    }

    //有効なユーザーかを検証
    public function verifyUser($user_id)
    {
        if ($user_id === null) {
            return ['error' => 'ログイン中のユーザー情報を取得できませんでした'];
        }
        return ['success' => true];
    }

    //指定のユーザーidが有効な整数かを検証
    public function verifySelectedUser($user_id, $selected_user)
    {
        if (!ctype_digit($user_id) || !isset($selected_user)) {
            return ['error' => '指定されたユーザー情報が見つかりませんでした'];
        }
        return ['success' => true];
    }
}