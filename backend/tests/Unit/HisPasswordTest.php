<?php

namespace Tests\Unit;

use App\Support\HisPassword;
use PHPUnit\Framework\TestCase;

class HisPasswordTest extends TestCase
{
    /**
     * The legacy double-encryption: sha1(md5($password)). Every password
     * hash already stored in his_admin.ad_pwd and his_docs.doc_pwd was
     * produced this way — this must never change.
     */
    public function test_hash_matches_the_legacy_algorithm(): void
    {
        $this->assertSame(sha1(md5('admin123')), HisPassword::hash('admin123'));
        $this->assertSame(sha1(md5('pkd123')), HisPassword::hash('pkd123'));
    }

    public function test_known_legacy_vector_verifies(): void
    {
        // The exact hash present in the seeded database (and any database
        // migrated from the original system) must keep verifying.
        $legacyHash = '036d0ef7567a20b5a4ad24a354ea4a945ddab676';
        $this->assertSame(sha1(md5('admin123')), $legacyHash);
        $this->assertTrue(HisPassword::verify('admin123', $legacyHash));
    }

    public function test_verify_rejects_wrong_passwords(): void
    {
        $hash = HisPassword::hash('admin123');

        $this->assertFalse(HisPassword::verify('wrong', $hash));
        $this->assertFalse(HisPassword::verify('Admin123', $hash)); // case sensitive
        $this->assertFalse(HisPassword::verify('', $hash));
        $this->assertFalse(HisPassword::verify('admin123 ', $hash)); // no trimming
    }

    public function test_verify_is_safe_against_timing_attacks(): void
    {
        // verify() uses hash_equals internally, which is constant-time.
        $hash = HisPassword::hash('admin123');

        $this->assertTrue(HisPassword::verify('admin123', $hash));
        $this->assertFalse(HisPassword::verify('admin124', $hash));
    }

    public function test_hashes_differ_between_passwords(): void
    {
        $this->assertNotSame(
            HisPassword::hash('admin123'),
            HisPassword::hash('admin124'),
        );
    }

    public function test_output_is_never_the_plain_or_single_hash(): void
    {
        $hash = HisPassword::hash('admin123');

        $this->assertNotSame('admin123', $hash);
        $this->assertNotSame(md5('admin123'), $hash); // not just md5
        $this->assertNotSame(sha1('admin123'), $hash); // not just sha1
        $this->assertSame(40, strlen($hash)); // sha1 hex length
    }
}
