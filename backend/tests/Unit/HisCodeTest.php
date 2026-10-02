<?php

namespace Tests\Unit;

use App\Support\HisCode;
use PHPUnit\Framework\TestCase;

class HisCodeTest extends TestCase
{
    /**
     * The legacy algorithm: substr(str_shuffle($charset), 1, $length).
     * These tests pin the exact behaviour that the original system
     * relied on — changing it would break existing data expectations.
     */
    public function test_generate_returns_exact_length(): void
    {
        foreach ([1, 5, 10, 30] as $length) {
            $code = HisCode::generate($length, HisCode::ALPHANUMERIC);
            $this->assertSame($length, strlen($code));
        }
    }

    public function test_generate_only_uses_the_legacy_charset(): void
    {
        $charset = HisCode::ALPHANUMERIC;
        $allowed = array_flip(str_split($charset));

        for ($i = 0; $i < 200; $i++) {
            $code = HisCode::generate(5, $charset);
            foreach (str_split($code) as $char) {
                $this->assertArrayHasKey($char, $allowed, "Unexpected character: {$char}");
            }
        }
    }

    public function test_record_numbers_are_five_char_uppercase_alphanumeric(): void
    {
        $methods = [
            'patientNumber', 'surgeryNumber', 'labNumber',
            'prescriptionNumber', 'medicalRecordNumber',
            'vitalNumber', 'payrollNumber', 'vendorNumber',
        ];

        foreach ($methods as $method) {
            for ($i = 0; $i < 20; $i++) {
                $code = HisCode::$method();
                $this->assertMatchesRegularExpression(
                    '/^[0-9A-Z]{5}$/',
                    $code,
                    "{$method} produced an unexpected code: {$code}"
                );
            }
        }
    }

    public function test_numeric_codes_are_five_digits(): void
    {
        // Legacy barcodes and account numbers: 5-digit numeric codes.
        for ($i = 0; $i < 50; $i++) {
            $code = HisCode::numericCode();
            $this->assertMatchesRegularExpression('/^[0-9]{5}$/', $code);
        }
    }

    public function test_reset_password_is_ten_chars_from_the_legacy_charset(): void
    {
        // Legacy his_admin_pwd_reset.php: temp passwords are 10 chars,
        // digits + upper + lower.
        $allowed = array_flip(str_split(HisCode::RESET_CHARSET));

        for ($i = 0; $i < 50; $i++) {
            $password = HisCode::resetPassword();
            $this->assertSame(10, strlen($password));
            foreach (str_split($password) as $char) {
                $this->assertArrayHasKey($char, $allowed);
            }
        }
    }

    public function test_reset_token_is_thirty_chars(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $this->assertSame(30, strlen(HisCode::resetToken()));
        }
    }

    public function test_generation_produces_varied_codes(): void
    {
        // A shuffled charset must not repeat the same code constantly.
        $codes = [];
        for ($i = 0; $i < 100; $i++) {
            $codes[HisCode::patientNumber()] = true;
        }
        $this->assertGreaterThan(90, count($codes), 'Code generation looks deterministic.');
    }
}
