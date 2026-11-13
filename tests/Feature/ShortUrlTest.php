<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Role;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(string $roleName, ?Company $company = null): User
    {
        $role = Role::firstOrCreate([
            'name' => $roleName,
        ]);

        if ($roleName !== 'SuperAdmin' && !$company) {
            $company = Company::create([
                'name' => 'Company ' . Str::random(6),
            ]);
        }

        return User::factory()->create([
            'role_id' => $role->id,
            'company_id' => $roleName === 'SuperAdmin' ? null : $company->id,
        ]);
    }

    private function createShortUrl(User $user, string $url, string $code): ShortUrl
    {
        return ShortUrl::create([
            'user_id' => $user->id,
            'original_url' => $url,
            'short_code' => $code,
        ]);
    }

    public function test_admin_can_create_short_url(): void
    {
        $admin = $this->createUser('Admin');

        $response = $this->actingAs($admin)->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/products',
        ]);

        $response->assertRedirect(route('short-urls.index'));

        $this->assertDatabaseHas('short_urls', [
            'user_id' => $admin->id,
            'original_url' => 'https://example.com/products',
        ]);
    }

    public function test_member_can_create_short_url(): void
    {
        $member = $this->createUser('Member');

        $response = $this->actingAs($member)->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/about',
        ]);

        $response->assertRedirect(route('short-urls.index'));

        $this->assertDatabaseHas('short_urls', [
            'user_id' => $member->id,
            'original_url' => 'https://example.com/about',
        ]);
    }

    public function test_super_admin_cannot_create_short_url(): void
    {
        $superAdmin = $this->createUser('SuperAdmin');

        $response = $this->actingAs($superAdmin)->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/products',
        ]);

        $response->assertForbidden();

        $this->assertDatabaseCount('short_urls', 0);
    }

    public function test_admin_can_only_see_short_urls_from_own_company(): void
    {
        $company = Company::create([
            'name' => 'Company One',
        ]);

        $admin = $this->createUser('Admin', $company);
        $companyMember = $this->createUser('Member', $company);
        $otherCompanyMember = $this->createUser('Member');

        $this->createShortUrl(
            $admin,
            'https://example.com/admin',
            'admin01'
        );

        $this->createShortUrl(
            $companyMember,
            'https://example.com/member',
            'member01'
        );

        $this->createShortUrl(
            $otherCompanyMember,
            'https://example.com/other-company',
            'other01'
        );

        $response = $this->actingAs($admin)
            ->get(route('short-urls.index'));

        $response->assertOk();
        $response->assertSee('https://example.com/admin');
        $response->assertSee('https://example.com/member');
        $response->assertDontSee('https://example.com/other-company');
    }

    public function test_member_can_only_see_own_short_urls(): void
    {
        $company = Company::create([
            'name' => 'Company One',
        ]);

        $member = $this->createUser('Member', $company);
        $otherMember = $this->createUser('Member', $company);

        $this->createShortUrl(
            $member,
            'https://example.com/my-url',
            'myurl01'
        );

        $this->createShortUrl(
            $otherMember,
            'https://example.com/other-url',
            'other02'
        );

        $response = $this->actingAs($member)
            ->get(route('short-urls.index'));

        $response->assertOk();
        $response->assertSee('https://example.com/my-url');
        $response->assertDontSee('https://example.com/other-url');
    }

    public function test_original_url_is_required(): void
    {
        $admin = $this->createUser('Admin');

        $response = $this->actingAs($admin)->post(route('short-urls.store'), [
            'original_url' => '',
        ]);

        $response->assertSessionHasErrors('original_url');

        $this->assertDatabaseCount('short_urls', 0);
    }

    public function test_original_url_must_be_valid(): void
    {
        $admin = $this->createUser('Admin');

        $response = $this->actingAs($admin)->post(route('short-urls.store'), [
            'original_url' => 'not-a-valid-url',
        ]);

        $response->assertSessionHasErrors('original_url');

        $this->assertDatabaseCount('short_urls', 0);
    }

    public function test_short_url_redirects_for_guest_and_logged_in_user(): void
    {
        $member = $this->createUser('Member');

        $originalUrl = 'https://sembark.com/travel-software/training';

        $shortUrl = $this->createShortUrl(
            $member,
            $originalUrl,
            'abc123'
        );

        // Guest user
        $this->get(route('short-urls.redirect', $shortUrl->short_code))
            ->assertRedirect($originalUrl);

        // Logged-in user
        $this->actingAs($member)
            ->get(route('short-urls.redirect', $shortUrl->short_code))
            ->assertRedirect($originalUrl);
    }

    public function test_super_admin_can_see_short_urls_from_all_companies(): void
    {
        $company1 = Company::create([
            'name' => 'Company One',
        ]);

        $company2 = Company::create([
            'name' => 'Company Two',
        ]);

        $superAdmin = $this->createUser('SuperAdmin');
        $admin1 = $this->createUser('Admin', $company1);
        $admin2 = $this->createUser('Admin', $company2);

        $shortUrl1 = $this->createShortUrl(
            $admin1,
            'https://example.com/company-one',
            'company01'
        );

        $shortUrl2 = $this->createShortUrl(
            $admin2,
            'https://example.com/company-two',
            'company02'
        );

        $response = $this->actingAs($superAdmin)
            ->get(route('short-urls.index'));

        $response->assertOk();

        $response->assertSee($shortUrl1->short_code);
        $response->assertSee($shortUrl2->short_code);
        $response->assertSee('Company One');
        $response->assertSee('Company Two');
    }
}
