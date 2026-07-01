<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LinkedRepo;
use App\Models\User;
use App\Jobs\SendQueuedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LinkedRepoApiController extends Controller
{
    /**
     * POST /api/links
     * Submit a new linked repository via API token.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'repo_url'    => ['required', 'url', 'regex:/^https?:\/\/(www\.)?github\.com\/[a-zA-Z0-9_.-]+\/[a-zA-Z0-9_.-]+/i'],
        ]);

        $user = $request->user();

        // Reject deactivated accounts even if they still hold a valid Sanctum token
        if (!$user || !$user->isActive()) {
            return response()->json(['message' => 'Your account has been deactivated.'], 403);
        }

        $repo = LinkedRepo::create([
            'user_id'     => $user->id,
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'repo_url'    => $validated['repo_url'],
            'status'      => 'pending',
        ]);

        // Notify admins
        $admins = User::where('role', 'admin')->where('status', 'active')->get();
        foreach ($admins as $admin) {
            try {
                SendQueuedMail::dispatch(
                    $admin->email,
                    'New Repository Submission via API - Ghost Compiler',
                    'emails.repo_submitted',
                    [
                        'adminName'    => $admin->name,
                        'submitter'    => $user->name,
                        'repoTitle'    => $repo->title,
                        'repoUrl'      => $repo->repo_url,
                        'dashboardUrl' => url('/admin'),
                    ]
                );
            } catch (\Throwable $e) {
                Log::error('API repo submission email failed', ['error' => $e->getMessage()]);
            }
        }

        return response()->json([
            'message' => 'Repository submitted successfully. It is pending administrator approval.',
            'data'    => [
                'id'          => $repo->id,
                'title'       => $repo->title,
                'repo_url'    => $repo->repo_url,
                'status'      => $repo->status,
                'submitted_at'=> $repo->created_at->toIso8601String(),
            ],
        ], 201);
    }
}
