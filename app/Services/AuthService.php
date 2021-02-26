<?php

namespace App\Services;

use App\Exceptions\ReportableException;
use App\Models\User;
use App\Models\UserToken;
use App\Utils\StringUtil;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Login method
     * @throws Exception
     */
    public function login(string $email, string $password): UserToken
    {
        $user = User::where('email', $email)->first();

        if (is_null($user) || !Hash::check($password, $user->password)) {
            throw new ReportableException("Invalid email or password.", null, 401);
        }

        $userToken = null;
        DB::beginTransaction();
        try {
            UserToken::where('user_id', $user->id)
            ->delete();
            $userToken = UserToken::create(['user_id' => $user->id]);
            DB::commit();
            return $userToken;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Register user
     * @return User
     * @throws ReportableException
     */
    public function register(string $name, string $email, string $personCompanyId, string $password): User
    {
        try {
            if (!StringUtil::isValidPersonCompanyId($personCompanyId)) {
                throw new ReportableException('Invalid person/company ID');
            }

            $user = User::create([
            'name' => $name,
            'email' => $email,
            'account_type' => strlen($personCompanyId) === 11 ? 1 : 2,
            'person_company_id' => $personCompanyId,
            'password' => Hash::make($password),
         ]);

            return $user;
        } catch (\Throwable $th) {
            throw ReportableException::from($th);
        }
    }
}
