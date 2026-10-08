# File Uploads - HTB Academy Guide

### Overview

File upload functionality is common in modern web applications, but improper validation can introduce serious security vulnerabilities.

This module covers how file-upload functionality can be tested, how different validation mechanisms work, how filters can be bypassed, and how insecure upload functionality can affect application security.

**Disclaimer:** These techniques are intended for authorized penetration testing, CTFs, cybersecurity labs, and educational purposes only.

---

## 1. Introduction

### What is a File Upload Vulnerability?

A file upload vulnerability occurs when a web application allows users to upload files without properly validating or restricting them.

Depending on how the uploaded file is handled, the vulnerability may lead to:

- Arbitrary file upload
- Stored XSS
- Sensitive information exposure
- File overwrite
- Path traversal
- Server-side code execution
- Potential Remote Code Execution (RCE)

The actual impact depends on the application's architecture and how uploaded files are processed.

---

## 2. Basic Exploitation

### Absent Validation

The first step is to understand how the application handles uploaded files.

Check:

- Allowed file extensions
- MIME type validation
- File-content validation
- Filename restrictions
- File-size restrictions
- Upload location
- Whether uploaded files are directly accessible
- Whether uploaded files are processed or executed

### Basic Testing Flow

**Identify Upload Functionality → Upload Legitimate File → Capture Request → Identify Validation → Test Restrictions → Identify Storage Location → Determine Server-Side Processing → Validate Security Impact**

### Upload Exploitation

If an application accepts files without proper validation, the uploaded content may be processed in an unsafe way.

During an authorized assessment, determine:

1. Can a file be uploaded?
2. Where is it stored?
3. Can the uploaded file be accessed?
4. How does the server process it?
5. Can the uploaded content affect the application?
6. What is the security impact?

The goal is to demonstrate the security impact safely rather than simply uploading a malicious file.

---

## 3. Bypassing Filters

Different applications implement different validation mechanisms.

### Client-Side Validation

Some applications perform validation using JavaScript before the upload request is sent.

Typical flow:

**Select File → JavaScript Validation → Upload Request**

Client-side validation should never be the only security control because the HTTP request can be modified before reaching the server.

**Testing:** Use Burp Suite to inspect and modify the actual upload request.

### Blacklist Filters

A blacklist blocks specific file extensions.

Examples:

- `.php`
- `.jsp`
- `.aspx`

The weakness of blacklist-based validation is that it can be incomplete if the application does not account for all dangerous file types or server-side behavior.

During testing, identify:

- Which extensions are blocked
- Which extensions are accepted
- Whether extension validation is case-sensitive
- Whether the server actually executes the uploaded file

### Whitelist Filters

A whitelist allows only explicitly approved file types.

Examples:

- `.jpg`
- `.png`
- `.pdf`

Whitelisting is generally stronger than relying only on a blacklist, but it should still be combined with additional server-side validation.

Check:

- Extension
- MIME type
- File signature
- File contents
- Filename
- Storage location
- Server-side processing

### Type Filters

Applications may attempt to validate files using:

- File extension
- MIME type
- File signature / magic bytes
- File contents

A secure implementation should not rely on a single client-controlled property to determine whether a file is safe.

---

## 4. Other Upload Attacks

### Limited File Uploads

Some applications restrict executable files but still allow formats that can introduce security issues.

Examples include:

- SVG
- HTML
- XML
- Image files
- Archive files

The impact depends on how the application processes the uploaded content.

### Other Upload Attack Scenarios

During an authorized assessment, consider:

- Filename manipulation
- Path traversal
- File overwrite
- Stored XSS
- Malicious file processing
- Metadata-based attacks
- Archive-related vulnerabilities
- Excessive file sizes
- Unauthorized access to uploaded files

---

## 5. Prevention

Secure file-upload functionality should use multiple layers of validation.

### Recommended Controls

**Allowlist:** Only permit required file types.

**Server-Side Validation:** Never rely exclusively on JavaScript validation.

**MIME & Content Validation:** Validate the actual content rather than trusting the filename.

**File Signature Validation:** Check file signatures / magic bytes where appropriate.

**Rename Uploaded Files:** Generate unpredictable filenames instead of trusting user-supplied names.

**Safe Storage:** Store uploaded files outside the web root where possible.

**Disable Execution:** Uploaded files should not be executable by the web server.

**Size Restrictions:** Apply reasonable file-size limits.

**Access Control:** Ensure users cannot access files belonging to other users.

### Secure Upload Architecture

**User Upload → Authentication → Authorization → Extension Allowlist → MIME Validation → Content Validation → File Signature Checking → Generate Safe Filename → Secure Storage → Prevent File Execution**

---

## 6. Testing Methodology

A practical file-upload security assessment can follow this process:

1. Identify upload functionality
2. Upload a legitimate file
3. Capture the request in Burp Suite
4. Identify validation mechanisms
5. Test client-side controls
6. Test server-side extension validation
7. Test MIME/content validation
8. Identify upload location
9. Check file accessibility
10. Determine server-side processing
11. Validate security impact
12. Document the vulnerability

---

## Tools

| Tool | Purpose |
|---|---|
| Burp Suite | Request interception and modification |
| FFUF | Content discovery and fuzzing |
| Gobuster | Directory/file enumeration |
| curl | Manual HTTP requests |
| file | File-type identification |
| ExifTool | Metadata analysis |

---

## Key Takeaways

- Client-side validation is not security.
- Extension validation alone is not enough.
- MIME type validation alone is not enough.
- Uploaded files should not be executable.
- File uploads should use multiple layers of validation.
- Uploaded files should be stored securely.
- Access controls should be applied to uploaded files.
- Always determine the actual security impact of the upload functionality.


## References

- OWASP File Upload Cheat Sheet
- OWASP Web Security Testing Guide
- PortSwigger Web Security Academy
- Hack The Box Academy — File Upload Attacks

---

### Disclaimer

These notes are intended for authorized penetration testing, CTF competitions, cybersecurity labs, security research, and educational purposes.

**Do not test systems without explicit authorization.**
