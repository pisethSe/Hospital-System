<?php

namespace App\Support;

/**
 * Legacy password hashing.
 *
 * The original application double-encrypted every password with
 * sha1(md5($password)) ("double encrypt to increase security") and the
 * business logic must not change. Existing password hashes in
 * his_admin.ad_pwd and his_docs.doc_pwd remain valid.
 */
class HisPassword
{
    /**
     * Hash a plain password using the legacy algorithm.
     */
    public static function hash(string $password): string
    {
        return sha1(md5($password));
    }

    /**
     * Verify a plain password against a legacy hash.
     */
    public static function verify(string $password, string $hashed): bool
    {
        return hash_equals($hashed, self::hash($password));
    }
}
