<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use App\Jobs\SendQueuedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('user')
            ->activeAndApproved()
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('blogs.index', compact('blogs'));
    }

    public function show(string $slug)
    {
        $blog = Blog::with('user')->where('slug', $slug)->firstOrFail();

        if ($blog->status !== 'approved' || !$blog->user->isActive()) {
            if (!Auth::check() || (!Auth::user()->isAdmin() && Auth::id() !== $blog->user_id)) {
                abort(403, 'This blog post is pending moderation or the author is inactive.');
            }
        }

        $recentBlogs = Blog::activeAndApproved()
            ->where('id', '!=', $blog->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('blogs.show', compact('blog', 'recentBlogs'));
    }

    public function create()
    {
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
        ]);

        $slug         = Str::slug($request->title);
        $originalSlug = $slug;
        $count        = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $isAdmin = Auth::user()->isAdmin();
        $status  = $isAdmin ? 'approved' : 'pending';

        $blog = Blog::create([
            'user_id' => Auth::id(),
            'title'   => $request->title,
            'slug'    => $slug,
            'summary' => $request->summary,
            'content' => $request->input('content'),
            'status'  => $status,
        ]);

        // Notify admins of new blog submission
        if (!$isAdmin) {
            $admins = User::where('role', 'admin')->where('status', 'active')->get();
            foreach ($admins as $admin) {
                try {
                    SendQueuedMail::dispatch(
                        $admin->email,
                        'New Blog Post Submitted - Ghost Compiler',
                        'emails.blog_submitted',
                        [
                            'adminName'    => $admin->name,
                            'submitter'    => Auth::user()->name,
                            'blogTitle'    => $blog->title,
                            'dashboardUrl' => route('admin.dashboard'),
                        ]
                    );
                } catch (\Throwable $e) {
                    Log::error('Failed to dispatch blog submitted email', ['error' => $e->getMessage()]);
                }
            }
        }

        if ($isAdmin) {
            return redirect()->route('blogs.show', $blog->slug)->with('success', 'Blog post published!');
        }

        return redirect()->route('dashboard')->with('success', 'Blog submitted successfully! Pending administrator review.');
    }
}
