<?php

namespace App\Support;

/**
 * Legacy compatibility helpers.
 *
 * The original PHP application used two algorithms everywhere and the
 * business logic must not change:
 *
 *  1. Record numbers (patients, surgery, lab, prescriptions, vendors,
 *     payrolls...) were generated with:
 *         substr(str_shuffle($charset), 1, $length)
 *
 *  2. Passwords were double-encrypted with:
 *         sha1(md5($password))
 *
 * Both are preserved here exactly, so existing database records keep
 * working after the migration.
 */
class HisCode
{
    /**
     * Character sets used by the legacy application.
     */
    public const ALPHANUMERIC = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public const DIGITS = '0123456789';

    /**
     * Legacy password-reset charset (his_admin_pwd_reset.php):
     * digits + upper + lower, used for temp passwords and tokens.
     */
    public const RESET_CHARSET = '0123456789QWERTYUIOPPLKJHGFDSAZCVBNMqwertyuioplkjhgfdsazxcvbnm';

    /**
     * Generate a legacy-style random code.
     *
     * Replicates: substr(str_shuffle($charset), 1, $length)
     */
    public static function generate(int $length = 5, string $charset = self::ALPHANUMERIC): string
    {
        return substr(str_shuffle($charset), 1, $length);
    }

    /**
     * Random patient number, e.g. "7EW0L".
     */
    public static function patientNumber(): string
    {
        return self::generate();
    }

    /**
     * Random surgery (theatre) number, e.g. "8KQWD".
     */
    public static function surgeryNumber(): string
    {
        return self::generate();
    }

    /**
     * Random laboratory number, e.g. "6P8HJ".
     */
    public static function labNumber(): string
    {
        return self::generate();
    }

    /**
     * Random prescription number.
     */
    public static function prescriptionNumber(): string
    {
        return self::generate();
    }

    /**
     * Random medical record number.
     */
    public static function medicalRecordNumber(): string
    {
        return self::generate();
    }

    /**
     * Random vitals record number.
     */
    public static function vitalNumber(): string
    {
        return self::generate();
    }

    /**
     * Random payroll number.
     */
    public static function payrollNumber(): string
    {
        return self::generate();
    }

    /**
     * Random vendor number.
     */
    public static function vendorNumber(): string
    {
        return self::generate();
    }

    /**
     * Random numeric code (legacy barcodes / account numbers).
     */
    public static function numericCode(int $length = 5): string
    {
        return self::generate($length, self::DIGITS);
    }

    /**
     * Legacy temporary password for password resets (10 chars).
     * Replicates: substr(str_shuffle(RESET_CHARSET), 1, $length_pwd)
     */
    public static function resetPassword(): string
    {
        return self::generate(10, self::RESET_CHARSET);
    }

    /**
     * Legacy reset token (30 chars).
     * Replicates: substr(str_shuffle(RESET_CHARSET), 1, $length_token)
     */
    public static function resetToken(): string
    {
        return self::generate(30, self::RESET_CHARSET);
    }
}
