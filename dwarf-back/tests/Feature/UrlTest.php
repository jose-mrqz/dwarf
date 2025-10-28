<?php

namespace Tests\Feature;

use App\Models\Url;
use App\Services\UrlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class UrlTest extends TestCase
{
    use RefreshDatabase;

    protected $urlService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlService = Mockery::mock(UrlService::class);
        $this->app->instance(UrlService::class, $this->urlService);
    }

    public function test_urls_index()
    {
        Url::factory()->count(3)->create();
        $route = route('urls.index');
        $response = $this->getJson($route);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'url', 'code'],
                ],
            ])
            ->assertJsonCount(3, 'data');
    }

    public function test_urls_show()
    {
        $url = Url::factory()->create();
        $route = route('urls.show', $url->id);
        $response = $this->getJson($route);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $url->id,
                'url' => $url->url,
                'code' => $url->code,
            ]);
    }

    public function test_urls_show_not_found()
    {
        $route = route('urls.show', 999);
        $response = $this->getJson($route);
        $response->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'URL not found',
            ]);
    }

    public function test_urls_show_by_code()
    {
        $url = Url::factory()->create(['url' => 'https://dwarf.io/test/url', 'code' => 'DWARF001']);
        $this->urlService->shouldReceive('getUrlByCode')
            ->once()
            ->with('DWARF001')
            ->andReturn($url);
        $route = route('urls.showByCode', 'DWARF001');
        $response = $this->getJson($route);
        $response->assertStatus(200)
            ->assertJsonFragment(['url' => 'https://dwarf.io/test/url', 'code' => 'DWARF001']);
    }

    public function test_urls_show_by_code_not_found()
    {
        $this->urlService->shouldReceive('getUrlByCode')
            ->once()
            ->with('NOT_FOUND')
            ->andReturn(null);
        $route = route('urls.showByCode', 'NOT_FOUND');
        $this->getJson($route)->assertNotFound();
    }

    public function test_urls_destroy()
    {
        $url = Url::factory()->create();
        $route = route('urls.destroy', $url->id);
        $response = $this->deleteJson($route);
        $response->assertStatus(204);
    }

    public function test_urls_destroy_not_found()
    {
        $route = route('urls.destroy', 999);
        $response = $this->deleteJson($route);
        $response->assertStatus(404);
    }

    public function test_urls_store_new_url()
    {
        $route = route('urls.store');
        $this->urlService->shouldReceive('shorten')
            ->once()
            ->with('https://dwarf.io/a-very-long-url')
            ->andReturn('LBXJIseu');
        $response = $this->postJson($route, [
            'url' => 'https://dwarf.io/a-very-long-url',
        ]);
        $response->assertStatus(201)
            ->assertJsonFragment([
                'id' => 1,
                'url' => 'https://dwarf.io/a-very-long-url',
                'code' => 'LBXJIseu',
            ]);
    }

    public function test_urls_store_invalid_url()
    {
        $route = route('urls.store');
        $response = $this->postJson($route, [
            'url' => 'invalid-url',
        ]);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'The url field must be a valid URL.',
            ]);
    }

    public function test_urls_redirect()
    {
        $url = Url::factory()->create(['url' => 'https://dwarf.io/test/url', 'code' => 'DWARF001']);
        $this->urlService->shouldReceive('getUrlByCode')
            ->once()
            ->with('DWARF001')
            ->andReturn($url);
        $route = route('urls.redirect', 'DWARF001');
        $response = $this->get($route)->assertRedirect('https://dwarf.io/test/url');
    }

    public function test_urls_redirect_not_found()
    {
        $this->urlService->shouldReceive('getUrlByCode')
            ->once()
            ->with('NOT_FOUND')
            ->andReturn(null);
        $route = route('urls.redirect', 'NOT_FOUND');
        $this->get($route)->assertNotFound();
    }
}
