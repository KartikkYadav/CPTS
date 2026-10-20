# Web Attacks

Structured notes for the **Web Attacks** section of my Hack The Box Academy CPTS preparation. This README is a navigation and revision guide for understanding common web application attack classes, testing them methodically, recording evidence, and recognizing effective mitigations.

> **Authorized use only:** Perform testing only in HTB Academy, CTFs, local intentionally vulnerable applications, or systems for which you have explicit written authorization. Replace `TARGET` and other placeholders with the values supplied by your lab. Do not test real users or production systems without approval.

---

## Table of Contents

- [Overview](#overview)
- [Testing Workflow](#testing-workflow)
- [Core Web Attack Areas](#core-web-attack-areas)
- [Request Inspection with Burp Suite](#request-inspection-with-burp-suite)
- [Useful Commands](#useful-commands)
- [Evidence and Reporting](#evidence-and-reporting)
- [Troubleshooting](#troubleshooting)
- [Defensive Checklist](#defensive-checklist)
- [Key Takeaways](#key-takeaways)
- [References](#references)

---

## Overview

Web attacks exploit weaknesses in how an application accepts input, processes requests, enforces access controls, handles files, or communicates with other systems.

A reliable assessment identifies the trust boundary, changes one variable at a time, compares results with a baseline, and validates impact with the least intrusive proof possible.

### Objectives

- Understand how HTTP requests reach application functionality.
- Identify input-handling and access-control weaknesses.
- Test application behaviour with controlled, repeatable requests.
- Distinguish a confirmed vulnerability from an unusual response.
- Document reproducible evidence and recommend practical remediation.

## Testing Workflow

1. **Confirm scope:** Record the permitted target, routes, accounts, and testing constraints.
2. **Map the application:** Browse normally and identify endpoints, forms, parameters, cookies, upload features, and API calls.
3. **Capture a baseline:** Save a normal request and response before testing.
4. **Form a hypothesis:** Identify the trust boundary or control that may be weak.
5. **Change one variable:** Modify a parameter, method, content type, identifier, or input value at a time.
6. **Compare results:** Review status codes, response body, response length, headers, redirects, and timing.
7. **Validate safely:** Use minimal proof in the lab and avoid destructive changes or unnecessary data access.
8. **Record evidence:** Preserve the exact request, payload, response, and screenshots.
9. **Recommend a fix:** Explain the root cause and the server-side control that should prevent it.
10. **Retest:** Repeat the original test after remediation to verify that the issue is resolved.

## Core Web Attack Areas

The following sections provide a revision checklist for common web-application security topics. Use the relevant module notes and lab instructions for target-specific steps.

| Attack area | What to examine | Defensive principle |
|---|---|---|
| HTTP method handling / verb tampering | Whether methods such as `GET` and `POST` reach different code paths or apply inconsistent checks | Enforce authorization and validation centrally for every supported method |
| Broken access control / IDOR | Whether changing an object identifier exposes another user's resource | Check authorization for every object on every request |
| Cross-site scripting (XSS) | Whether untrusted input is interpreted as executable browser content | Context-aware output encoding, safe DOM APIs, and appropriate Content Security Policy |
| SQL injection | Whether input changes the structure of a database query | Parameterized queries and least-privilege database accounts |
| OS command injection | Whether user input changes a command interpreted by an operating-system shell | Avoid shell invocation; pass structured arguments and validate inputs |
| Server-side request forgery (SSRF) | Whether user-controlled URLs or destinations can make the server access unintended resources | Destination allowlists, egress controls, and safe URL parsing |
| XML external entity (XXE) | Whether XML parsers resolve external entities or access external resources | Disable external entity resolution and use secure parser configurations |
| File upload weaknesses | Whether upload validation, storage, or processing permits unsafe files or paths | Allowlist file types, verify content, rename files, store outside web roots, and isolate processing |
| Path traversal / file inclusion | Whether path input can escape an intended directory or include unintended resources | Canonicalize paths and enforce an approved base directory |
| Authentication and session weaknesses | Whether login, recovery, or session handling can be bypassed or abused | Strong authentication, secure session management, rate limits, and safe recovery flows |
| Server-side template injection (SSTI) | Whether user input is evaluated as template syntax | Keep data separate from templates and avoid evaluating user-controlled template expressions |

A response difference by itself does not prove a vulnerability. Confirm that the suspected security boundary was crossed and that the result is reproducible.

## Request Inspection with Burp Suite

Burp Suite helps capture, inspect, replay, and compare HTTP requests in an authorized lab.

1. Configure the browser to use Burp Suite Proxy.
2. Browse the application normally and capture the relevant request.
3. Send the request to **Repeater**.
4. Save the original request as a baseline.
5. Modify one field at a time, such as the method, parameter value, object identifier, or content type.
6. Compare the responses and retain the request/response pairs that support the finding.
7. Remove secrets and personal data before including evidence in a public report.

### Example baseline request

Use the real path and host provided by the lab:

```http
GET /example?id=100 HTTP/1.1
Host: TARGET
User-Agent: Mozilla/5.0
Accept: */*
Connection: close
```

This is an illustrative request only. The endpoint and parameter are placeholders, not a claim that a particular target is vulnerable.

## Useful Commands

### Send a basic HTTP request

```bash
curl -i 'http://TARGET/'
```

- `-i` includes response headers.
- Replace `TARGET` with the authorized lab host.

### Inspect a specific endpoint

```bash
curl -i 'http://TARGET/example?id=100'
```

Use only endpoints and identifiers within your authorized scope. A successful response does not establish that access controls are correct.

### Send a form-encoded POST request

```bash
curl -i -X POST 'http://TARGET/example' \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  --data-urlencode 'name=test'
```

- `-X POST` sets the HTTP method.
- `--data-urlencode` encodes the form value.
- Match the application's actual request format and required parameters.

### Follow redirects

```bash
curl -i -L 'http://TARGET/'
```

- `-L` follows HTTP redirects.
- When investigating authentication or access-control behaviour, inspect intermediate responses too; redirects may hide useful details.

> **Note:** Avoid putting session cookies, access tokens, passwords, or API keys directly into shell history or public notes. Redact sensitive values in shared evidence.

## Evidence and Reporting

For every finding, capture:

- **Title:** A concise name describing the weakness.
- **Affected endpoint:** URL, method, and relevant parameter.
- **Prerequisites:** Required account role, session, or application state.
- **Objective:** What the test is intended to verify.
- **Baseline:** The original request and response.
- **Test case:** The exact modified request, input, or payload.
- **Observed result:** Status code, response content, and other relevant behaviour.
- **Impact:** What an attacker could actually access or change, based on verified evidence.
- **Reproduction steps:** Numbered steps another tester can repeat.
- **Remediation:** A specific server-side correction.
- **Retest result:** Whether the correction prevents the original behaviour.

Keep requests, payloads, outputs, and screenshots accurate. Do not invent output, modify evidence to make a finding appear stronger, or include unrelated secrets.

## Troubleshooting

### Problem: The response does not change

**Symptoms**
- The response appears identical to the baseline.
- The suspected input does not affect application behaviour.

**Possible causes**
- The selected parameter is not used by the relevant code path.
- The application normalizes or rejects the input.
- A proxy or framework changes how the request is handled.
- The test does not reach the suspected functionality.

**Checks**
- Confirm the exact request in Burp Suite.
- Verify the parameter name, location, and content type.
- Change one variable at a time and compare against a saved baseline.
- Use the application and lab instructions to identify the intended route.

### Problem: The server returns `403 Forbidden` or `401 Unauthorized`

**Symptoms**
- A test request is rejected.
- The application redirects to login or returns an authorization error.

**Possible causes**
- The session is missing or expired.
- The account lacks the required permission.
- A CSRF token or other request-specific value is required.
- The endpoint intentionally blocks the operation.

**Checks**
- Capture a fresh, legitimate request from the application.
- Compare cookies, authorization headers, CSRF tokens, and request body fields.
- Do not attempt to access accounts or resources outside the approved lab scope.

### Problem: Different HTTP methods produce different results

**Symptoms**
- A method returns a different status code, response body, or redirect.

**Possible causes**
- The route supports only certain methods.
- Middleware or application handlers differ by method.
- The body format does not match the selected method.
- Authorization or validation is inconsistently applied.

**Checks**
- Confirm which methods the endpoint is designed to support.
- Keep the path, session, and parameters consistent while comparing methods.
- Verify whether a protected operation actually succeeded; a status-code difference alone is not proof of a bypass.

## Defensive Checklist

- [ ] Enforce authentication and authorization on the server for every request.
- [ ] Apply object-level access checks to every resource identifier.
- [ ] Use parameterized queries for database access.
- [ ] Avoid passing untrusted strings to an operating-system shell.
- [ ] Encode untrusted output according to its HTML, JavaScript, URL, or other context.
- [ ] Validate inputs with strict, server-side rules.
- [ ] Use secure XML parser settings and avoid unsafe template evaluation.
- [ ] Restrict server-side network destinations and outbound traffic.
- [ ] Validate uploaded files, store them safely, and isolate file processing.
- [ ] Keep error messages and logs from exposing credentials or sensitive internals.
- [ ] Apply controls consistently across all supported HTTP methods and API routes.
- [ ] Retest fixes with the same reproducible request that demonstrated the issue.

## Key Takeaways

- Understand the request flow and trust boundaries before testing.
- Save a baseline and change one variable at a time.
- Confirm impact with repeatable evidence rather than assumptions.
- A status code, error message, or response-time difference alone is not conclusive.
- Access control must be enforced server-side for every resource and operation.
- Strong remediation addresses the root cause instead of relying on client-side checks or character blacklists.

---

## References

- [OWASP Web Security Testing Guide](https://owasp.org/www-project-web-security-testing-guide/)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [OWASP Cheat Sheet Series](https://cheatsheetseries.owasp.org/)
- [PortSwigger Web Security Academy](https://portswigger.net/web-security)
- [Hack The Box Academy](https://academy.hackthebox.com/)

---

**Author:** [Kartik Yadav](https://github.com/KartikkYadav)  
**Repository:** [CPTS](https://github.com/KartikkYadav/CPTS)  
**Purpose:** HTB Academy CPTS preparation, CTFs, and authorized security-lab practice.
