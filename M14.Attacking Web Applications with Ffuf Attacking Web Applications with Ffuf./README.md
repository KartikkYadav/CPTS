# Web Application Fuzzing with FFUF - HTB Academy Guide

A practical CPTS module focused on **FFUF-based web fuzzing** for directories, pages, recursive paths, subdomains, virtual hosts, parameters, POST requests, and response filtering.

---

## 📚 Module Overview

The module develops a progression from basic content discovery to application-specific fuzzing:

**Directory Fuzzing → Page Fuzzing → Recursive Fuzzing → Subdomain Fuzzing → VHost Fuzzing → Response Filtering → Parameter Fuzzing → POST/Value Fuzzing → HTB Walkthrough**

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [Directory Fuzzing with FFUF](./01.Directory%20Fuzzing%20with%20FFUF.md) | Hidden directories, files, status filtering, response-size filtering |
| 02 | [Page Fuzzing with FFUF](./02.%20Page%20Fuzzing%20with%20FFUF.md) | Web-page and file discovery |
| 03 | [Recursive Fuzzing with FFUF](./03.Recursive%20Fuzzing%20with%20FFUF.md) | Recursive directory and endpoint discovery |
| 04 | [Subdomain Fuzzing with FFUF](./04.Subdomain%20Fuzzing%20with%20FFUF.md) | DNS-based subdomain discovery |
| 05 | [VHost Fuzzing with FFUF](./05.%20VHost%20Fuzzing%20with%20FFUF.md) | Host-header-based virtual-host discovery |
| 06 | [Filtering Results in FFUF](./06.Filtering%20Results%20in%20FFUF.md) | Matchers, filters, response sizes, and reducing false positives |
| 07 | [Parameter Fuzzing](./07.Parameter%20Fuzzing.md) | Discovering hidden GET/POST parameters |
| 08 | [POST Requests & Value Fuzzing](./08.POST%20Requests%20%26%20Value%20Fuzzing.md) | POST parameter discovery and value fuzzing |
| 09 | [HTB Web Fuzzing Walkthrough Notes](./09.%20HTB%20Web%20Fuzzing%20Walkthrough%20Notes.md) | Integrated vhost, extension, recursive, parameter, and value fuzzing lab |

---

## 🔎 01. Directory Fuzzing

**[Directory Fuzzing with FFUF](./01.Directory%20Fuzzing%20with%20FFUF.md)** introduces FFUF as a fast web-fuzzing tool.

### Core Options

| Option | Purpose |
|---|---|
| `-w` | Wordlist |
| `-u` | Target URL |
| `-mc` | Match status codes |
| `-fc` | Filter status codes |
| `-fs` | Filter response size |
| `-t` | Threads |
| `-o` | Output file |
| `-H` | Header |
| `-X` | HTTP method |
| `-d` | Request body |

### FUZZ Keyword

FFUF inserts each wordlist entry wherever the special keyword `FUZZ` appears.

Example:

```bash
ffuf -w <wordlist>:FUZZ -u http://SERVER_IP:PORT/FUZZ
```

### Common Results

The notes use HTTP status codes such as:

**200 · 301/302 · 401 · 403 · 404**

to identify potentially interesting responses.

---

## 📄 02. Page Fuzzing

**[Page Fuzzing with FFUF](./02.%20Page%20Fuzzing%20with%20FFUF.md)** extends content discovery from directories to individual web pages and files.

The purpose is to discover resources that are not obvious from normal browsing.

Typical targets include:

- Application pages
- Admin pages
- Backup files
- Configuration files
- Alternate endpoints

---

## 🔁 03. Recursive Fuzzing

**[Recursive Fuzzing with FFUF](./03.Recursive%20Fuzzing%20with%20FFUF.md)** covers recursive discovery.

The notes use recursive mode to continue fuzzing inside directories discovered by the first stage.

Important controls include:

```text
-recursion
-recursion-depth
```

Recursive fuzzing is especially useful when the location of the interesting page is unknown.

---

## 🌐 04. Subdomain Fuzzing

**[Subdomain Fuzzing with FFUF](./04.Subdomain%20Fuzzing%20with%20FFUF.md)** focuses on discovering public DNS subdomains.

Conceptually:

```text
Wordlist
   ↓
FUZZ.domain.tld
   ↓
DNS / HTTP Validation
   ↓
Interesting Hostnames
```

This helps expand the visible application attack surface.

---

## 🏠 05. VHost Fuzzing

**[VHost Fuzzing with FFUF](./05.%20VHost%20Fuzzing%20with%20FFUF.md)** focuses on virtual hosts selected through the HTTP `Host` header.

Example pattern:

```bash
ffuf -w <wordlist>:FUZZ -u http://TARGET/ -H "Host: FUZZ.example.htb"
```

The module distinguishes VHost discovery from public DNS subdomain discovery.

### Why It Matters

A web server may host multiple applications on the same IP while some hostnames are not publicly published in DNS.

---

## 🎯 06. Filtering Results

**[Filtering Results in FFUF](./06.Filtering%20Results%20in%20FFUF.md)** covers one of the most important parts of practical fuzzing: reducing false positives.

### Matchers

Matchers identify responses you want to keep.

### Filters

Filters remove known-uninteresting responses.

Common examples covered across the module include:

```text
-mc    Match status
-fc    Filter status
-fs    Filter size
```

### Response-Size Filtering

When every invalid request returns the same page size, `-fs` can remove those results and expose responses that behave differently.

Conceptually:

```text
Thousands of Requests
       ↓
Default Response
       ↓
Filter
       ↓
Small Set of Interesting Results
```

---

## 🔢 07. Parameter Fuzzing

**[Parameter Fuzzing](./07.Parameter%20Fuzzing.md)** focuses on discovering parameters that are not visible in the normal application interface.

The notes explain that applications may accept hidden:

- GET parameters
- POST parameters
- Administrative fields
- Debug variables
- Internal functionality

### Core Process

**Discover Endpoint → Fuzz Parameter Names → Detect Response Difference → Verify Manually**

---

## 📮 08. POST Requests & Value Fuzzing

**[POST Requests & Value Fuzzing](./08.POST%20Requests%20%26%20Value%20Fuzzing.md)** focuses on POST requests and the transition from parameter-name fuzzing to parameter-value fuzzing.

### GET vs POST

**GET:** parameters commonly appear in the URL.

**POST:** parameters are commonly supplied in the request body.

### FFUF POST Structure

The notes use:

```bash
-X POST
-d 'FUZZ=key'
-H 'Content-Type: application/x-www-form-urlencoded'
```

### Value Fuzzing

After discovering a valid parameter, the next phase is testing different values.

Conceptually:

```text
Parameter Discovery
      ↓
Valid Parameter
      ↓
Value Wordlist
      ↓
Interesting Response
      ↓
Manual Validation
```

The notes connect predictable parameter values and object identifiers with further access-control testing such as IDOR scenarios.

---

## 🧪 09. HTB Web Fuzzing Walkthrough

**[HTB Web Fuzzing Walkthrough Notes](./09.%20HTB%20Web%20Fuzzing%20Walkthrough%20Notes.md)** combines the individual techniques into an integrated lab.

### Lab Workflow

```text
Add Base Domain to /etc/hosts
          ↓
Subdomain Fuzzing
          ↓
VHost Fuzzing
          ↓
Extension Fuzzing
          ↓
Recursive Page Fuzzing
          ↓
Parameter Fuzzing
          ↓
Value Fuzzing
```

The walkthrough demonstrates why fuzzing often needs to be performed in layers rather than as one large scan.

---

## 🧭 Practical FFUF Workflow

A reusable workflow based on the module is:

### 1. Confirm Scope

Identify the domain, IP, ports, and authorized application paths.

### 2. Identify a Baseline Response

Determine what a normal invalid request looks like.

### 3. Start Content Discovery

Fuzz directories and pages.

### 4. Enumerate Hostnames

Test subdomains and VHosts separately.

### 5. Fuzz Extensions

When the technology stack suggests multiple possible file extensions, test them explicitly.

### 6. Filter Noise

Use status-code and response-size filters to reduce false positives.

### 7. Discover Parameters

Fuzz GET/POST parameter names.

### 8. Fuzz Values

Test values for confirmed parameters.

### 9. Manually Validate

Send promising requests through cURL, Burp Suite, or another HTTP client and verify the behavior.

---

## 🧠 FFUF Memory Cheatsheet

| Goal | Core FFUF Concept |
|---|---|
| Wordlist input | `-w` |
| Target | `-u` |
| Payload position | `FUZZ` |
| Match status | `-mc` |
| Filter status | `-fc` |
| Filter size | `-fs` |
| Threads | `-t` |
| Header | `-H` |
| HTTP method | `-X` |
| POST body | `-d` |
| Recursive mode | `-recursion` |

---

## 🧠 Key Learning Points

### Establish a Baseline First

Without knowing the normal response, it is difficult to identify meaningful fuzzing results.

### Filtering Is Essential

The speed of FFUF can produce large amounts of output. Proper filtering turns noisy output into useful results.

### VHosts and Subdomains Are Different

DNS-based subdomain discovery and HTTP Host-header fuzzing should be treated as separate techniques.

### Fuzz in Layers

Directory discovery, extension discovery, parameter discovery, and value fuzzing each answer a different question.

### Always Verify Interesting Results

A different status code or response size is a clue, not proof of a security vulnerability.

---

## 🛠️ Tools Covered

**FFUF · cURL · SecLists · Burp Suite**

---

## 📖 Recommended Study Order

**1. Directory Fuzzing → 2. Page Fuzzing → 3. Recursive Fuzzing → 4. Subdomain Fuzzing → 5. VHost Fuzzing → 6. Filtering → 7. Parameter Fuzzing → 8. POST/Value Fuzzing → 9. Walkthrough**

---

## ⚠️ Responsible Use

Use FFUF only against:

**HTB Academy · CPTS Labs · CTFs · Security Labs · Authorized Web Applications**

High thread counts, recursive scans, and large wordlists can generate substantial traffic. Keep fuzzing within scope and tune request rates appropriately.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Web Application Security | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for FFUF-based content discovery, hostname fuzzing, parameter discovery, filtering, and web-application fuzzing workflows.**
