# Command Injection - HTB Academy Guide

Notes for the Hack The Box Academy CPTS module **Command Injection**. This guide covers the underlying vulnerability, discovery, validation, common payload patterns, filter-bypass concepts, and remediation.

> **Scope and safety:** Run the examples only against your own applications, intentionally vulnerable labs, or systems for which you have explicit authorization. Replace placeholders such as `TARGET` and `PARAM` with values from the lab. Do not run destructive commands on production systems.

---

## Table of Contents

- [Overview](#overview)
- [How Command Injection Works](#how-command-injection-works)
- [Types of Command Injection](#types-of-command-injection)
- [Testing Methodology](#testing-methodology)
- [Common Shell Operators](#common-shell-operators)
- [Payload Examples](#payload-examples)
- [Filter Bypass and HTTP Verb Tampering](#filter-bypass-and-http-verb-tampering)
- [Useful Commands](#useful-commands)
- [Troubleshooting](#troubleshooting)
- [Prevention](#prevention)
- [Key Takeaways](#key-takeaways)

---

## Overview

**OS command injection** occurs when an application constructs an operating-system command using untrusted input and passes that command to a shell. If input is interpreted as shell syntax, an attacker may be able to alter the command that the application executes.

Potential impact includes:

- Reading files accessible to the application process.
- Executing commands with the application's operating-system privileges.
- Accessing environment variables or application secrets.
- Pivoting to other systems when the compromised process has network access.
- Disrupting service availability or modifying data.

The actual impact depends on the application's privileges, the execution environment, and available security controls.

## How Command Injection Works

A vulnerable application might concatenate user input into a shell command:

```python
# Vulnerable example — do not use in production
import os

hostname = request.args.get("host", "")
os.system("ping -c 1 " + hostname)
```

If the application passes the constructed string to a shell, shell metacharacters in the input may change how the command is parsed.

A safer design avoids the shell and passes arguments separately:

```python
import subprocess
import ipaddress

host = request.args.get("host", "")
ipaddress.ip_address(host)  # Validate that the input is an IP address

subprocess.run(
    ["ping", "-c", "1", host],
    shell=False,
    check=False,
    timeout=5,
)
```

Input validation should match the application's real requirements. Avoid invoking a shell when a direct library or API can perform the operation.

## Types of Command Injection

| Type | Description | Typical signal |
|---|---|---|
| In-band / direct | Command output appears in the application response. | Unexpected output is returned. |
| Error-based | Command execution changes error messages or status codes. | Different errors or response behaviour. |
| Blind, time-based | Output is not shown, but execution affects response time. | Repeatable delay under controlled tests. |
| Blind, out-of-band | Execution causes an observable interaction with a controlled external service. | A lab-controlled DNS or HTTP callback. |

A single unusual response is not proof. Compare baseline and test requests, repeat safely, and rule out ordinary application or network delays.

## Testing Methodology

1. **Confirm scope.** Identify the exact lab host, endpoint, and permitted test window.
2. **Map inputs.** Review query parameters, form fields, JSON values, headers, and filenames that may reach an operating-system process.
3. **Establish a baseline.** Send a normal request and record the status code, response body, response length, and timing.
4. **Use a harmless validation string.** In a controlled lab, test whether shell syntax changes application behaviour without modifying files or disrupting services.
5. **Compare responses.** Check output, errors, status codes, and timing against the baseline.
6. **Test alternate request handling.** If the lab suggests a method-specific filter, compare allowed HTTP methods while keeping other request fields constant.
7. **Document evidence.** Save the complete request, exact payload, response, and screenshot. Redact secrets and unrelated personal data.
8. **Validate impact minimally.** Use the least intrusive proof required by the lab; avoid persistence, destructive commands, or broad data extraction.

## Common Shell Operators

Shell parsing differs between environments. These are common examples for POSIX-like shells; they may not behave the same way in Windows command interpreters or when the application does not invoke a shell.

| Operator | General meaning |
|---|---|
| `;` | Separates commands; the following command can run regardless of the previous command's exit status. |
| `&&` | Runs the next command only if the previous command succeeds. |
| `||` | Runs the next command only if the previous command fails. |
| `|` | Pipes output from one command to another. |
| `$(...)` | Performs command substitution in many POSIX shells. |
| `\n` | A newline may act as a command separator in some parsing contexts. |
| `&` | May background a command in POSIX shells; its meaning differs across contexts. |

URL encoding, quoting, escaping, and input normalization can change how characters are interpreted. A filter that blocks a few characters is not a reliable security boundary.

## Payload Examples

Use these only in an authorized, intentionally vulnerable lab. The examples illustrate syntax and response comparison; they are not tied to a specific target.

### 1. Baseline request

Record a normal request before introducing test syntax:

```http
GET /example?host=127.0.0.1 HTTP/1.1
Host: TARGET
```

### 2. Harmless command-separator check

In a lab where the parameter is known to be used in a POSIX shell, compare the normal response with a harmless separator test:

```text
127.0.0.1; echo INJECTION_TEST
```

If the marker appears in output or otherwise changes behaviour, investigate further and preserve the evidence. If it does not appear, command injection is not necessarily ruled out; output may be suppressed or filtered.

### 3. Blind timing check

Only use a short delay in a controlled lab and compare repeated requests against the baseline:

```text
127.0.0.1; sleep 3
```

A timing difference alone is inconclusive. Repeat a small number of times and account for network latency, rate limits, and application load.

### 4. URL encoding

When testing through a URL, special characters may need percent encoding. For example:

| Character | Encoded form |
|---|---|
| Space | `%20` |
| `;` | `%3B` |
| `&` | `%26` |
| `|` | `%7C` |
| Newline | `%0A` |

Encoding is not an exploit by itself. The server may decode input before validation, decode it more than once, or pass it through a framework that handles it differently. Record the raw request and the server's observed behaviour.

## Filter Bypass and HTTP Verb Tampering

Some labs place a filter in front of a vulnerable operation. A common learning objective is to determine whether validation is applied consistently across request methods or input locations.

### HTTP Verb Tampering

HTTP verb tampering tests whether an application enforces security checks consistently for methods such as `GET`, `POST`, and `PUT`.

1. Capture the original request in Burp Suite or another authorized HTTP client.
2. Record the method, path, headers, content type, parameters, and response.
3. Change only the HTTP method, where the lab permits it.
4. Keep the relevant parameter values constant and compare responses.
5. Check whether authentication, authorization, validation, and filtering are enforced by the same server-side logic for every accepted method.

A different response does not automatically prove a bypass. The server may legitimately support different behaviour for each method. Confirm that the protected operation was actually reached and document the evidence.

### Filename and File-Handling Inputs

If a lab explicitly identifies a filename as the injection point, determine how that value is validated, decoded, normalized, and passed to the underlying process. Do not assume a parameter named `file` must contain a literal filename such as `file.txt`; use the lab instructions and the observed request format to establish the expected value.

**Defensive lesson:** Reject unexpected input using strict allowlists, avoid shell interpretation, and apply the same validation to every route and HTTP method that reaches the operation.

## Useful Commands

### Inspect a request with curl

```bash
# Baseline request; replace TARGET and the path with lab values.
curl -i 'http://TARGET/example?host=127.0.0.1'
```

- `-i` includes response headers in the output.
- Quote the URL so the local shell does not interpret special characters.
- Use HTTPS and the correct authentication/session headers when required by the lab.

### Send a POST request

```bash
curl -i -X POST 'http://TARGET/example' \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  --data-urlencode 'host=127.0.0.1'
```

- `-X POST` sets the HTTP method.
- `--data-urlencode` encodes the form value for transport.
- Keep request parameters aligned with the actual application's interface.

### Capture a raw HTTP request

Use Burp Suite Proxy or Repeater to inspect and modify requests. Save the original request before testing variations. Redact session cookies, API keys, and other credentials before adding evidence to a public repository.

## Troubleshooting

### Problem: The payload is displayed but no command appears to run

**Symptoms / Error**
- The marker is missing from the response.
- The response looks identical to the baseline.

**Possible causes**
- The input is not used in a shell command.
- Output is suppressed or redirected.
- The application uses a different shell or operating system.
- Validation, quoting, or encoding changes the input.
- The vulnerable code path was not reached.

**Checks**
- Confirm the parameter name and request format.
- Compare the exact baseline and test requests.
- Review status codes, response lengths, errors, and timing.
- Use only the lab's documented environment and allowed validation steps.

### Problem: The request returns a different error after changing the method

**Symptoms / Error**
- `405 Method Not Allowed`, `403 Forbidden`, or a different application response.

**Possible causes**
- The route does not support that method.
- A proxy, framework, or security middleware handles methods differently.
- The request body or content type is incompatible with the selected method.
- Authentication or CSRF validation failed.

**Checks**
- Restore the baseline request and change one variable at a time.
- Confirm the expected method from the application or lab instructions.
- Do not treat a status-code change alone as proof of command execution.

### Problem: Encoded characters do not behave as expected

**Symptoms / Error**
- A request is rejected or the server receives a different value than expected.

**Possible causes**
- The client encoded the value differently than intended.
- The server decodes or normalizes input before validation.
- The framework parses the value as a form field, path, or query parameter.

**Checks**
- Inspect the raw request in an HTTP proxy.
- Verify the content type and parameter location.
- Record the exact encoded request and response.

## Prevention

- **Avoid shell execution.** Prefer language APIs and libraries over operating-system commands.
- **Pass arguments as an array.** If a subprocess is necessary, use an API that does not invoke a shell (for example, Python's `subprocess.run([...], shell=False)`).
- **Validate with allowlists.** Enforce the expected data type, format, and length on the server.
- **Do not rely on blacklists.** Blocking a handful of shell metacharacters is fragile and can be bypassed by differences in parsing and encoding.
- **Apply least privilege.** Run application processes with only the filesystem and operating-system permissions they need.
- **Constrain execution.** Use appropriate container, sandbox, filesystem, and network controls where applicable.
- **Handle errors safely.** Do not expose command lines, environment variables, secrets, or internal paths to users.
- **Log and monitor.** Record validation failures and suspicious process execution without logging credentials or sensitive payload data.
- **Test every entry point.** Apply consistent controls across routes, HTTP methods, file-handling flows, and background jobs.

## Key Takeaways

- Command injection occurs when untrusted input changes the operating system command interpreted by an application.
- Confirm findings with controlled, repeatable evidence rather than relying on a single response.
- Understand the target shell and request parsing behaviour before interpreting payload results.
- HTTP verb tampering tests consistency of server-side controls; a different response alone is not a confirmed bypass.
- The strongest mitigation is to avoid shell invocation, use structured argument passing, validate inputs, and apply least privilege.
- Keep lab reports reproducible: include the request, exact input, response, screenshots, and a clear explanation of impact.

---

## References

- [OWASP: OS Command Injection Defense Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/OS_Command_Injection_Defense_Cheat_Sheet.html)
- [OWASP: Injection](https://owasp.org/www-community/Injection_Flaws)
- [Python: subprocess documentation](https://docs.python.org/3/library/subprocess.html)

---

**Author:** [Kartik Yadav](https://github.com/KartikkYadav)  
**Repository:** [CPTS](https://github.com/KartikkYadav/CPTS)  
**Purpose:** HTB Academy CPTS preparation and authorized security-lab practice.
