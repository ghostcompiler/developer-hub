<?php

namespace App\Http\Controllers;

use League\CommonMark\GithubFlavoredMarkdownConverter;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    /**
     * Display the Privacy Policy.
     */
    public function privacy()
    {
        return $this->renderPolicy(
            'privacy-policy.md',
            'Privacy Policy',
            $this->defaultPrivacyPolicy()
        );
    }

    /**
     * Display the Terms of Service.
     */
    public function terms()
    {
        return $this->renderPolicy(
            'terms-of-service.md',
            'Terms of Service',
            $this->defaultTermsOfService()
        );
    }

    /**
     * Display the Terms and Conditions.
     */
    public function conditions()
    {
        return $this->renderPolicy(
            'terms-and-conditions.md',
            'Terms & Conditions',
            $this->defaultTermsAndConditions()
        );
    }

    /**
     * Load a policy file, convert it to HTML, and return the view.
     */
    protected function renderPolicy(string $filename, string $title, string $default): \Illuminate\Contracts\View\View
    {
        $filePath = base_path($filename);
        $markdown = file_exists($filePath) ? file_get_contents($filePath) : $default;

        $html = $this->convertToHtml($markdown);

        return view('policies.layout', [
            'title'         => $title,
            'htmlContent'   => $html,
            'effectiveDate' => 'June 17, 2026',
        ]);
    }

    /**
     * Convert Markdown to safe HTML using GFM (with table support).
     */
    protected function convertToHtml(string $markdown): string
    {
        try {
            $converter = new GithubFlavoredMarkdownConverter([
                'html_input'         => 'strip',  // Strip raw HTML for XSS safety
                'allow_unsafe_links' => false,
            ]);
            return $converter->convert($markdown)->getContent();
        } catch (\Exception $e) {
            return '<p>' . nl2br(e($markdown)) . '</p>';
        }
    }

    // ── Default content (shown if .md file not found on disk) ──────────────

    protected function defaultPrivacyPolicy(): string
    {
        return "# Privacy Policy\n\n*Last updated: " . date('F j, Y') . "*\n\n"
            . "At **Ghost Compiler**, we respect your privacy. Please see the file `privacy-policy.md` in the project root.";
    }

    protected function defaultTermsOfService(): string
    {
        return "# Terms of Service\n\n*Last updated: " . date('F j, Y') . "*\n\n"
            . "Welcome to **Ghost Compiler**. Please see `terms-of-service.md` in the project root.";
    }

    protected function defaultTermsAndConditions(): string
    {
        return "# Terms & Conditions\n\n*Last updated: " . date('F j, Y') . "*\n\n"
            . "Please see `terms-and-conditions.md` in the project root.";
    }
}
