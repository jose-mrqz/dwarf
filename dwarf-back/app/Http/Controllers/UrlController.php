<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrlRequest;
use App\Http\Resources\UrlResource;
use App\Models\Url;
use App\Services\UrlService;

/**
 * @OA\Info(
 *     title="URL Shortener API",
 *     version="1.0.0",
 *     description="API for managing and shortening URLs"
 * )
 * @OA\Schema(
 *     schema="Url",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="url", type="string", example="https://example.com"),
 *     @OA\Property(property="code", type="string", example="abc123"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class UrlController extends Controller
{
    private $urlService;

    public function __construct(UrlService $urlService)
    {
        $this->urlService = $urlService;
    }

    /**
     * @OA\Get(
     *     path="/api/urls",
     *     summary="List all URLs",
     *     tags={"URLs"},
     *     @OA\Response(
     *         response=200,
     *         description="List of URLs",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Url")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $urls = Url::all();
        return UrlResource::collection($urls);
    }

    /**
     * @OA\Post(
     *     path="/api/urls",
     *     summary="Create a shortened URL",
     *     tags={"URLs"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="url", type="string", example="https://example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="URL created",
     *         @OA\JsonContent(ref="#/components/schemas/Url")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreUrlRequest $request)
    {
        $validated = $request->validated();
        $code = $this->urlService->shorten($validated['url']);
        $url = Url::create([
            'url' => $validated['url'],
            'code' => $code,
        ]);
        return UrlResource::make($url);
    }

    /**
     * @OA\Get(
     *     path="/api/urls/{id}",
     *     summary="Get a URL by ID",
     *     tags={"URLs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="URL details",
     *         @OA\JsonContent(ref="#/components/schemas/Url")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="URL not found")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $url = Url::find($id);
        if (!$url) {
            return response()->json(['message' => 'URL not found'], 404);
        }
        return UrlResource::make($url);
    }

    /**
     * @OA\Get(
     *     path="/api/urls/code/{code}",
     *     summary="Get a URL by code",
     *     tags={"URLs"},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="URL details",
     *         @OA\JsonContent(ref="#/components/schemas/Url")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="URL not found")
     *         )
     *     )
     * )
     */
    public function showByCode(string $code)
    {
        $url = $this->urlService->getUrlByCode($code);
        if (!$url) {
            return response()->json(['message' => 'URL not found'], 404);
        }
        return response()->json(UrlResource::make($url), 200);
    }

    /**
     * @OA\Get(
     *     path="/{code}",
     *     summary="Redirect to URL by code",
     *     tags={"URLs"},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirects to the original URL"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL not found"
     *     )
     * )
     */
    public function redirect(string $code)
    {
        $url = $this->urlService->getUrlByCode($code);
        if ($url) {
            return redirect()->away($url->url);
        }
        abort(404);
    }

    /**
     * @OA\Delete(
     *     path="/api/urls/{id}",
     *     summary="Delete a URL",
     *     tags={"URLs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="URL deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL not found"
     *     )
     * )
     */
    public function destroy(Url $url)
    {
        $url->delete();
        return response()->noContent();
    }
}