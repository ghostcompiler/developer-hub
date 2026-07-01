# Terms & Conditions

**Last updated: June 17, 2026** · Effective: June 17, 2026

These Terms & Conditions ("T&C") govern the specific rules and conditions of using **ghostcompiler.in**, operated by Ghost Compiler. These T&C supplement the Terms of Service and Privacy Policy and apply to all users, visitors, and API consumers of the platform.

---

## 1. Acceptance of Terms

By accessing or using any part of ghostcompiler.in — including browsing the repository directory, reading blog posts, creating an account, submitting content, or consuming the API — you unconditionally accept these Terms & Conditions, the Terms of Service, and the Privacy Policy in full.

If you are using the platform on behalf of a company or organisation, you represent that you have the authority to bind that entity to these terms, and "you" refers to that entity.

---

## 2. Intellectual Property Rights

### 2.1 Platform Code & Design

The Ghost Compiler platform — including but not limited to its source code, front-end design system, UI components, CSS styles, admin dashboard, and branding assets — constitutes **proprietary intellectual property** of Ghost Compiler. You may not:

- Copy, reproduce, redistribute, or resell any part of the platform code or design
- Create derivative works based on the platform without explicit written permission
- Use Ghost Compiler's branding, logo, or name in a way that implies endorsement or affiliation

### 2.2 Open-Source Projects in the Directory

Open-source packages, SDKs, plugins, and tools listed in the Ghost Compiler directory are the intellectual property of their respective authors and contributors. Each project is governed by its own open-source licence as declared in its repository (e.g., MIT Licence, Apache 2.0, GNU GPL). Ghost Compiler:

- Does **not** claim ownership of any listed open-source project
- Does **not** modify or redistribute listed project code
- Indexes only publicly available metadata and documentation for discoverability purposes

### 2.3 Blog Content

Blog posts published on the platform remain the intellectual property of their authors. By publishing on Ghost Compiler, you grant the platform a non-exclusive licence to display your post. You retain the right to republish your content elsewhere, provided it does not infringe on any third party's rights.

---

## 3. Account Suspension & Termination

### 3.1 Grounds for Suspension or Termination

Ghost Compiler reserves the right to deactivate, suspend, or permanently terminate user accounts and access to the platform under any of the following circumstances:

- **Violation of content guidelines** — submitting spam repositories, fake projects, or harmful blog posts
- **Security threats** — attempting to exploit vulnerabilities, bypass authentication, or access other users' data
- **Abuse of API access** — circumventing rate limits, token stuffing, or automated spam submissions
- **Impersonation** — falsely claiming to be another developer, project maintainer, or organisation
- **Legal compliance** — when required by applicable law, court order, or regulatory authority
- **Repeated violations** — pattern of behaviour that violates these terms even if individually minor

### 3.2 Effect of Termination

Upon account termination:
- Your ability to log in and access the dashboard is immediately revoked
- Your API tokens are invalidated
- Your submitted content (blog posts, repository links) may be retained for archival or integrity purposes, or deleted at administrator discretion
- You may contact **hello@ghostcompiler.in** to request deletion of all your associated data

### 3.3 Appeals

If you believe your account was suspended or terminated in error, you may submit an appeal to **hello@ghostcompiler.in** with a clear explanation. We will review appeals within 7 business days.

---

## 4. API Access & Usage

### 4.1 API Token Issuance

Verified users may generate personal API tokens from the Ghost Compiler developer dashboard. Each token:

- Is tied to your individual account
- Grants access to submit new repository links via the REST API
- Carries the same content guidelines and usage restrictions as manual submissions

### 4.2 Rate Limits

API usage is subject to rate limiting:

| Endpoint | Limit |
|---|---|
| POST /api/links (repository submission) | 30 requests per minute |

Exceeding rate limits will result in a `429 Too Many Requests` response. Persistent abuse of rate limits may result in token revocation and account suspension.

### 4.3 Token Security

You are solely responsible for keeping your API tokens secure. If a token is compromised:
- Delete it immediately from your dashboard
- Generate a new token
- Contact **hello@ghostcompiler.in** if you suspect malicious submissions were made using the compromised token

---

## 5. Developer Content Quality Guidelines

All content submitted to Ghost Compiler — blog posts and repository links — must meet the following quality standards:

### 5.1 Repository Submissions

- Must be a genuine, functional open-source project hosted on GitHub
- Must include a README file with a meaningful description
- Must not duplicate an existing listing already in the directory
- Must be actively maintained or clearly marked as archived by the original author
- The repository URL must resolve to a public, accessible GitHub repository

### 5.2 Blog Posts

- Must be original content written by the submitting user
- Must be technically relevant to software development, open-source tools, or related developer topics
- Must not be AI-generated spam, promotional fluff, or content unrelated to the platform's developer audience
- Must not contain affiliate links, hidden redirects, or deceptive outbound links

---

## 6. Disclaimer of Warranties

Ghost Compiler makes no representation or warranty, express or implied, regarding:

- The **accuracy, completeness, or fitness for purpose** of any open-source project listed in the directory
- The **security** of any linked package — users are responsible for evaluating the safety of any open-source code they install or use
- **Uninterrupted availability** of the platform or API
- The **correctness** of information contained in user-submitted blog posts

Any use of software packages discovered via Ghost Compiler is entirely at your own risk. Ghost Compiler is a discovery and documentation platform, not a software vendor or code auditor.

---

## 7. Limitation of Liability

To the maximum extent permitted by applicable law, Ghost Compiler, its operators, and contributors shall not be liable for any:

- **Direct damages** arising from the use of or inability to use the platform
- **Indirect, incidental, or consequential damages** including data loss, system downtime, or business interruption
- **Third-party claims** arising from the use of any open-source project listed on the platform
- **Security vulnerabilities** discovered in linked open-source repositories

If you discover a security vulnerability in any listed project, we encourage responsible disclosure directly to that project's maintainers.

---

## 8. Indemnification

You agree to indemnify, defend, and hold harmless Ghost Compiler and its operators from any claims, damages, losses, liabilities, costs, and expenses (including reasonable legal fees) arising from:

- Your use of the platform in violation of these terms
- Content you submit that infringes a third party's intellectual property or other rights
- Any misrepresentation you make in connection with the platform

---

## 9. Changes to These Terms

Ghost Compiler reserves the right to update these Terms & Conditions at any time. When material changes are made:

- The "Last updated" date at the top of this page will be revised
- For significant changes, we will attempt to notify registered users via email
- Your continued use of the platform after changes are posted constitutes acceptance of the revised terms

We recommend bookmarking this page and reviewing it periodically.

---

## 10. Severability

If any provision of these Terms & Conditions is found to be unenforceable or invalid by a court of competent jurisdiction, that provision shall be modified to the minimum extent necessary to make it enforceable. All other provisions shall remain in full force and effect.

---

## 11. Entire Agreement

These Terms & Conditions, together with the Terms of Service and Privacy Policy, constitute the entire agreement between you and Ghost Compiler regarding your use of the platform and supersede all prior agreements, representations, or understandings relating to the same subject matter.

---

## 12. Governing Law & Jurisdiction

These Terms & Conditions are governed by the laws of **India**. You agree to submit to the exclusive jurisdiction of the courts of India for any legal matters arising from these terms or your use of the platform.

---

## 13. Contact Information

For questions, concerns, or legal inquiries regarding these Terms & Conditions:

- **Email:** hello@ghostcompiler.in
- **Website:** https://ghostcompiler.in
- **Response time:** Within 48 business hours

*Ghost Compiler — Built for developers, by developers.*
