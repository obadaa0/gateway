<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\MediaHelper;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class ReportController extends Controller
{
    public function show(Report $report,Request $request)
    {
    $report = Report::with('User')->paginate(10);
    return $report;
    }
    // public function show(Request $request)
    // {
    //     $numberOfReport = $request->input('per_page',10);
    //     $user = User::whereHas('reports')->with('reports')->paginate($numberOfReport);
    //     return $user;
    // }

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
        $validData['media'] = MediaHelper::StoreMedia('reports',$request);
        $user = AuthHelper::getUserFromToken($request);
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
