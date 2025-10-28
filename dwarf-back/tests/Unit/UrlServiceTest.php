<?php

namespace Tests\Unit;

use App\Models\Url;
use App\Services\UrlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlServiceTest extends TestCase
{
    use RefreshDatabase;

    private UrlService $urlService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->urlService = new UrlService();
    }

    public function test_shorten_same_code_for_same_url()
    {
        $url = 'https://dwarf.io/test-url';
        
        $code1 = $this->urlService->shorten($url);
        $code2 = $this->urlService->shorten($url);
        
        $this->assertEquals($code1, $code2);
        $this->assertNotEmpty($code1);
        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]+$/', $code1);
    }

    public function test_shorten_different_code_for_different_urls()
    {
        $url1 = 'https://dwarf.io/test-url-1';
        $url2 = 'https://dwarf.io/test-url-2';
        $code1 = $this->urlService->shorten($url1);
        $code2 = $this->urlService->shorten($url2);
        $this->assertNotEquals($code1, $code2);
    }

    public function test_shorten_very_long_url()
    {
        $url = 'https://dwarf.io/' . str_repeat('very-long-path/', 10);
        $code = $this->urlService->shorten($url);
        $this->assertNotEmpty($code);
        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]+$/', $code);
        $this->assertLessThanOrEqual(8, strlen($code));
    }

    public function test_shorten_contains_query_params()
    {
        $url = 'https://dwarf.io/test?param=value&other=123#fragment';
        $code = $this->urlService->shorten($url);
        $this->assertNotEmpty($code);
        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z]+$/', $code);
        $this->assertLessThanOrEqual(8, strlen($code));
    }
}
