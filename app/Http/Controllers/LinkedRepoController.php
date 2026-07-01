<?php

namespace App\Http\Controllers;

use App\Models\LinkedRepo;
use App\Models\User;
use App\Jobs\SendQueuedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LinkedRepoController extends Controller
{
    public function create()
    {
        return view('repos.link');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'repo_url'    => ['required', 'url', 'regex:/^https?:\/\/(www\.)?github\.com\/[a-zA-Z0-9_.-]+\/[a-zA-Z0-9_.-]+/i'],
        ], [
            'repo_url.regex' => 'Must be a valid GitHub URL (e.g. https://github.com/user/repo).',
        ]);

        $isAdmin = Auth::user()->isAdmin();
        $status  = $isAdmin ? 'approved' : 'pending';

        $repo = LinkedRepo::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'repo_url'    => $request->repo_url,
            'status'      => $status,
        ]);

        // Notify all admins a new repo was submitted (only when pending)
        if (!$isAdmin) {
            $admins = User::where('role', 'admin')->where('status', 'active')->get();
            foreach ($admins as $admin) {
                try {
                    SendQueuedMail::dispatch(
                        $admin->email,
                        'New Repository Submission - Ghost Compiler',
                        'emails.repo_submitted',
                        [
                            'adminName'   => $admin->name,
                            'submitter'   => Auth::user()->name,
                            'repoTitle'   => $repo->title,
                            'repoUrl'     => $repo->repo_url,
                            'dashboardUrl' => route('admin.dashboard'),
                        ]
                    );
                } catch (\Throwable $e) {
                    Log::error('Failed to dispatch repo submitted email', ['error' => $e->getMessage()]);
                }
            }
        }

        return redirect()->route('dashboard')->with(
            'success',
            $isAdmin
                ? 'Repository linked and indexed successfully!'
                : 'Repository submitted! It is pending administrator approval.'
        );
    }
}
