<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private int $userCounter = 0;

    private function createUser(array $overrides = []): User
    {
        $this->userCounter++;

        return User::create(array_merge([
            'name' => 'Test User ' . $this->userCounter,
            'username' => 'testuser' . $this->userCounter,
            'email' => "test{$this->userCounter}@example.com",
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_kepala' => false,
        ], $overrides));
    }

    public function test_user_has_fillable_attributes(): void
    {
        $user = new User();
        $fillable = $user->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('username', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('password', $fillable);
        $this->assertContains('role', $fillable);
        $this->assertContains('pangkalan_id', $fillable);
        $this->assertContains('is_kepala', $fillable);
    }

    public function test_user_password_is_hidden(): void
    {
        $user = new User();
        $hidden = $user->getHidden();

        $this->assertContains('password', $hidden);
        $this->assertContains('remember_token', $hidden);
    }

    public function test_user_is_kepala_cast_to_boolean(): void
    {
        $user = $this->createUser(['is_kepala' => 1]);
        $this->assertIsBool($user->is_kepala);
        $this->assertTrue($user->is_kepala);

        $user2 = $this->createUser(['is_kepala' => 0]);
        $this->assertFalse($user2->is_kepala);
    }

    public function test_can_access_penilaian_area_as_admin(): void
    {
        $user = $this->createUser(['role' => 'admin', 'is_kepala' => false]);
        $this->assertTrue($user->canAccessPenilaianArea());
    }

    public function test_can_access_penilaian_area_as_kepala(): void
    {
        $user = $this->createUser(['role' => 'user', 'is_kepala' => true]);
        $this->assertTrue($user->canAccessPenilaianArea());
    }

    public function test_cannot_access_penilaian_area_as_regular_user(): void
    {
        $user = $this->createUser(['role' => 'user', 'is_kepala' => false]);
        $this->assertFalse($user->canAccessPenilaianArea());
    }

    public function test_cannot_access_penilaian_area_as_tata_usaha(): void
    {
        $user = $this->createUser(['role' => 'tata_usaha', 'is_kepala' => false]);
        $this->assertFalse($user->canAccessPenilaianArea());
    }

    public function test_is_tata_usaha_returns_true(): void
    {
        $user = $this->createUser(['role' => 'tata_usaha']);
        $this->assertTrue($user->isTataUsaha());
    }

    public function test_is_tata_usaha_returns_false_for_other_roles(): void
    {
        $admin = $this->createUser(['role' => 'admin']);
        $this->assertFalse($admin->isTataUsaha());

        $user = $this->createUser(['role' => 'user']);
        $this->assertFalse($user->isTataUsaha());
    }

    public function test_role_label_for_kepala(): void
    {
        $user = $this->createUser(['is_kepala' => true, 'role' => 'user']);
        $this->assertEquals('Kepala Pimpinan Pos', $user->role_label);
    }

    public function test_role_label_for_admin(): void
    {
        $user = $this->createUser(['role' => 'admin', 'is_kepala' => false]);
        $this->assertEquals('Admin', $user->role_label);
    }

    public function test_role_label_for_tata_usaha(): void
    {
        $user = $this->createUser(['role' => 'tata_usaha', 'is_kepala' => false]);
        $this->assertEquals('Tata Usaha', $user->role_label);
    }

    public function test_role_label_for_regular_user(): void
    {
        $user = $this->createUser(['role' => 'user', 'is_kepala' => false]);
        $this->assertEquals('User', $user->role_label);
    }

    public function test_role_label_for_unknown_role(): void
    {
        $user = $this->createUser(['role' => 'unknown', 'is_kepala' => false]);
        $this->assertEquals('User', $user->role_label);
    }

    public function test_get_all_pangkalan_ids_empty_when_no_pangkalan(): void
    {
        $user = $this->createUser(['pangkalan_id' => null]);
        $this->assertEmpty($user->getAllPangkalanIds());
    }

    public function test_get_all_pangkalan_ids_includes_primary_pangkalan(): void
    {
        $pangkalan = \App\Models\Pangkalan::create([
            'kode_pangkalan' => 'PK001',
            'nama_pangkalan' => 'Pangkalan Test',
        ]);

        $user = $this->createUser(['pangkalan_id' => $pangkalan->id]);
        $ids = $user->getAllPangkalanIds();

        $this->assertContains($pangkalan->id, $ids);
    }

    public function test_get_all_pangkalan_ids_includes_kepala_pangkalan(): void
    {
        $pangkalan1 = \App\Models\Pangkalan::create([
            'kode_pangkalan' => 'PK001',
            'nama_pangkalan' => 'Pangkalan 1',
        ]);
        $pangkalan2 = \App\Models\Pangkalan::create([
            'kode_pangkalan' => 'PK002',
            'nama_pangkalan' => 'Pangkalan 2',
        ]);

        $user = $this->createUser(['pangkalan_id' => $pangkalan1->id]);
        $user->kepalaPangkalan()->attach($pangkalan2->id);

        $ids = $user->getAllPangkalanIds();

        $this->assertContains($pangkalan1->id, $ids);
        $this->assertContains($pangkalan2->id, $ids);
    }

    public function test_get_all_pangkalan_ids_deduplicates(): void
    {
        $pangkalan = \App\Models\Pangkalan::create([
            'kode_pangkalan' => 'PK001',
            'nama_pangkalan' => 'Pangkalan Test',
        ]);

        $user = $this->createUser(['pangkalan_id' => $pangkalan->id]);
        $user->kepalaPangkalan()->attach($pangkalan->id);

        $ids = $user->getAllPangkalanIds();

        $this->assertCount(1, $ids);
    }

    public function test_karyawan_relationship(): void
    {
        $user = $this->createUser();
        $this->assertNull($user->karyawan);
    }

    public function test_pangkalan_relationship(): void
    {
        $pangkalan = \App\Models\Pangkalan::create([
            'kode_pangkalan' => 'PK001',
            'nama_pangkalan' => 'Pangkalan Test',
        ]);

        $user = $this->createUser(['pangkalan_id' => $pangkalan->id]);
        $this->assertNotNull($user->pangkalan);
        $this->assertEquals($pangkalan->id, $user->pangkalan->id);
    }

    public function test_kepala_pangkalan_relationship(): void
    {
        $user = $this->createUser();
        $this->assertCount(0, $user->kepalaPangkalan);
    }

    public function test_password_is_hashed_on_create(): void
    {
        $user = $this->createUser(['password' => Hash::make('mypassword')]);
        $this->assertNotEquals('mypassword', $user->password);
        $this->assertTrue(Hash::check('mypassword', $user->password));
    }

    public function test_user_has_timestamps(): void
    {
        $user = $this->createUser();
        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
    }
}
