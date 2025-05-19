<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class ReportController extends Controller
{
    public function index(Report $report,Request $request)
    {
        $report->User;
    return $report;
    }
    public function show(Request $request)
    {
        $numberOfReport = $request->input('per_page');
        return Report::paginate($numberOfReport);
    }
    public function create(Request $request)
    {
        try{
            $validData = $request->validate([
                'description' => 'required|string',
                'media' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e){
            return response()->json(['message' => $e->errors()]);
        }
            if ($request->hasFile('media')) {
                $media = $request->file('media');
                $mediaName = Str::random(20) . '.' . $media->getClientOriginalExtension();
                $directory = public_path('storage/reports');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $media->move($directory, $mediaName);
                $path = asset('storage/reports/' . $mediaName);
                $validData['media'] =$path;
               }
        $token  = PersonalAccessToken::findToken($request->bearerToken());
        if(!$token){
            return response()->json(['message' => "unAuth"]);
        }
        $user = $token->tokenable;
        if(!$user){
            return response()->json(['message' => "unAuth"]);
        }
       $report = $user->reports()->create($validData);
        return response()->json(['message'=>'report send successfully', 'data' =>$report],200);
    }

    public function setProgress(Report $report)
    {
         $report->progress();
        return $report;
        }
    public function setResolved(Report $report)
    {
        $report->resolved();
        return $report;
    }

}
