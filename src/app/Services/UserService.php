<?php
namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(
        UserRepository $userRepository
    ){
        $this->userRepository = $userRepository;
    }

    public function getUsers()
    {
        return $this->userRepository->paginateUsers();
    }

    public function searchUsers($keyword)
    {
        return $this->userRepository->paginateUsersByKeyword($keyword);
    }

    public function getUserName($id)
    {
        $user = $this->userRepository->findUser($id);
        return $user->name;
    }
}