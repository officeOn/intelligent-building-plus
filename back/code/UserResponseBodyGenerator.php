<?php
/**
 * The UserResponseBodyGenerator file.
 */
namespace sgk\back;

/**
 * Class UserResponseBodyGenerator.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class UserResponseBodyGenerator
{
    /**
     * @param UserModel[] $users
     *
     * @return array
     */
    public static function generateBulkBody(array $users)
    {
        $bulkBody = [];

        foreach ($users as $user) {
            $bulkBody[] = static::generateBody($user);
        }

        return $bulkBody;
    }

    /**
     * @param UserModel $user
     *
     * @return array
     */
    public static function generateBody(UserModel $user)
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'type' => $user->getType(),
            'usefulReportCount' => $user->getUsefulReportCount(),
            'totalReportCount' => $user->getTotalReportCount(),
        ];
    }
}
