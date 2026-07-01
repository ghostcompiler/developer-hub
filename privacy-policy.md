# Privacy Policy

**Last updated: June 17, 2026** · Effective: June 17, 2026

At **Ghost Compiler** (ghostcompiler.in), your privacy is not just a legal formality — it is a core part of how we build our platform. This Privacy Policy explains what data we collect, why we collect it, how it is protected, and what rights you have over it.

---

## 1. Who We Are

**Ghost Compiler** is an independent open-source developer platform operated at [https://ghostcompiler.in](https://ghostcompiler.in). We publish open-source SDKs, Laravel packages, Plesk extensions, developer utilities, and technical documentation.

For privacy-related inquiries, contact us at **hello@ghostcompiler.in**.

---

## 2. Data We Collect

We collect only the data necessary to provide our services. We do not collect data for advertising, profiling, or sale to third parties.

### 2.1 Account Registration Data

When you register directly on our platform, we collect:

- **Full name** — to personalise your profile and communications
- **Email address** — for account identification, verification, notifications, and two-factor authentication
- **Password (hashed)** — stored as a bcrypt hash; we never store plain-text passwords

### 2.2 OAuth / Social Login Data

When you sign in via **Google** or **GitHub**, we receive from the OAuth provider only:

- Your **name** and **email address** as authorised by the provider
- A **provider-specific user ID** (opaque numeric/string identifier), stored to link your provider account to your Ghost Compiler account
- We do **not** receive or store your social account password, phone number, followers, or any other profile data

### 2.3 Content You Submit

- **Blog Posts** — title, summary, and Markdown-formatted content you publish on the platform
- **Repository Links** — the GitHub repository URL, title, and description you voluntarily submit for indexing
- **API Tokens** — cryptographic tokens you generate from your dashboard (stored as hashed values; plaintext is shown only once at generation time)

### 2.4 Repository Indexing Data

When a repository is linked and approved, we fetch and store:

- Repository **metadata** (name, description, star count, language, topics, license) from the GitHub API
- The repository's **README** file and linked documentation pages (Markdown converted to safe HTML)
- Latest **release information** (tag name, version, published date) from public GitHub Releases API

This data is sourced entirely from public GitHub APIs and is identical to what any visitor to the GitHub page can see.

### 2.5 Session & Technical Data

- **Session data** — a server-side Laravel session cookie to maintain login state and two-factor verification status
- **Two-Factor OTP** — a 6-digit time-limited one-time password stored temporarily in your server-side session (never in the database)
- **Theme preference** — stored in your browser's `localStorage` only (never sent to our server)
- **Server logs** — standard web server access logs (IP address, request path, HTTP status, timestamp) for security and debugging

---

## 3. How We Use Your Data

| Purpose | Legal Basis |
|---|---|
| Authenticating your login sessions | Contractual necessity |
| Sending email verification links | Contractual necessity |
| Sending two-factor authentication codes | Contractual necessity / Security |
| Sending password reset links | Contractual necessity |
| Displaying your profile and content on the platform | Contractual necessity |
| Indexing and displaying linked GitHub repositories | User consent (explicit submission) |
| Notifying administrators of new repository submissions | Legitimate interest |
| Maintaining server security logs | Legitimate interest |

We **do not** use your data for:
- Advertising or behavioural profiling
- Selling or renting to any third party
- Training machine learning or AI models
- Marketing to unrelated third parties

---

## 4. Cookies & Local Storage

### Cookies We Set

| Cookie | Purpose | Duration |
|---|---|---|
| `ghostcompiler_session` | Laravel encrypted session (authentication, CSRF, 2FA state) | Session / configurable |
| `XSRF-TOKEN` | Cross-Site Request Forgery protection | Session |

### Local Storage

| Key | Purpose |
|---|---|
| `color-theme` | Stores your light/dark mode preference locally in your browser |

We use **no tracking cookies**, **no analytics cookies** (Google Analytics, Hotjar, etc.), and **no advertising cookies**.

---

## 5. Data Retention

| Data Type | Retention Period |
|---|---|
| Account data | Until you delete your account |
| Blog posts | Until you delete them or your account is removed |
| Linked repository records | Until removed by you or an admin |
| Server access logs | Up to 90 days, then purged |
| Two-factor OTP session values | 10 minutes, then auto-expired |
| Password reset tokens | 60 minutes, then invalidated |
| Email verification tokens | 60 minutes, then expired |

---

## 6. Data Sharing

We share your data with **no third parties** for commercial or analytical purposes. The only limited sharing that occurs is:

- **GitHub API** — We send repository URLs to the GitHub API to fetch metadata. GitHub's own Privacy Policy governs that interaction.
- **Google OAuth / GitHub OAuth** — When you use social login, you are redirected to those providers. We receive only the minimal data described in §2.2.
- **Email delivery** — If configured, our mail server (e.g., SMTP provider) processes the email address necessary to deliver verification and OTP emails.
- **Hosting provider** — Our web hosting provider processes connection data as part of delivering the site. No user data is shared beyond standard server operation.

---

## 7. Security Measures

We implement industry-standard security practices:

- All passwords stored as **bcrypt hashes** — never in plaintext
- All connections served over **HTTPS / TLS**
- **CSRF tokens** on all state-changing forms
- **Rate limiting** on login, registration, password reset, OTP, and API endpoints
- **Mandatory two-factor authentication** for all accounts (TOTP app or email OTP)
- **Signed and time-limited** email verification links (expire after 60 minutes)
- **Cryptographically secure** OTP generation using PHP's `random_int()`
- **XSS protection** — all user-submitted Markdown is sanitised before rendering
- **SQL injection protection** via Laravel's parameterised query system

---

## 8. Your Rights

Depending on your location, you may have the right to:

- **Access** — Request a copy of the personal data we hold about you
- **Rectification** — Correct inaccurate data in your profile via the dashboard
- **Erasure** — Request deletion of your account and associated data
- **Restriction** — Request that we restrict processing of your data
- **Portability** — Request your data in a machine-readable format
- **Objection** — Object to processing based on legitimate interest

To exercise any of these rights, email us at **hello@ghostcompiler.in** with the subject line "Privacy Request". We will respond within **30 days**.

---

## 9. Children's Privacy

Ghost Compiler is a developer-focused platform intended for users aged **18 and above**. We do not knowingly collect personal data from children under 18. If you believe we have inadvertently collected data from a minor, contact us immediately at **hello@ghostcompiler.in** and we will delete it promptly.

---

## 10. Changes to This Policy

We may update this Privacy Policy to reflect changes in our practices or applicable law. When we do, we will update the "Last updated" date at the top of this page. Your continued use of the platform after changes are posted constitutes your acceptance of the updated policy.

---

## 11. Contact Us

If you have any questions, concerns, or requests regarding this Privacy Policy, please contact us:

- **Email:** hello@ghostcompiler.in
- **Website:** https://ghostcompiler.in
- **Response time:** Within 48 business hours

*Ghost Compiler is committed to transparency and responsible data stewardship.*
