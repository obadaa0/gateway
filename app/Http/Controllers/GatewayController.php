<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
public function handle(Request $request)
{
    $path = $request->path();
    $method = strtolower($request->method());
    $baseUrl = str_starts_with($path, 'api/admin/') || str_starts_with($path, 'api/police/')
        ? env('DASHBOARD_API_URL')
        : env('MAIN_API_URL');
    $url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    $http = Http::timeout(100)->withHeaders([
        'Authorization' => $request->header('Authorization'),
        'Accept' => 'application/json',
    ]);
    try {
        $hasFiles = count($request->files->all()) > 0;
        if ($hasFiles) {
            $multipart = [];
            foreach ($request->all() as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        $multipart[] = ['name' => $key . '[]', 'contents' => $subValue];
                    }
                } else {
                    $multipart[] = ['name' => $key, 'contents' => $value];
                }
            }
            foreach ($request->files->all() as $key => $file) {
                if (is_array($file)) {
                    foreach ($file as $subFile) {
                        $multipart[] = [
                            'name' => $key . '[]',
                            'contents' => fopen($subFile->getPathname(), 'r'),
                            'filename' => $subFile->getClientOriginalName(),
                        ];
                    }
                } else {
                    $multipart[] = [
                        'name' => $key,
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ];
                }
            }
            $response = $http->asMultipart()->$method($url, $multipart);
        } elseif (in_array($method, ['post', 'put', 'patch', 'delete'])) {
            $response = $http->$method($url, $request->all());
        } else {
            $response = $http->$method($url, ['query' => $request->all()]);
        }
        return response($response->body(), $response->status())
            ->withHeaders($response->headers());

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Gateway Error',
            'message' => 'Not Found URL',
        ], 404);
    }
}
}
