# SQLMap - HTB Academy Guide

A focused CPTS module on **SQLMap**, covering SQL Injection detection, DBMS fingerprinting, injection techniques, output interpretation, request handling, authentication context, cookies, custom headers, and complex web/API requests.

---

## 📚 Module Overview

**SQLMap Fundamentals → Injection Techniques → Output Analysis → Request Preparation → Advanced Input Handling**

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [SQLMap Overview](./01.%20SQLMap%20Overview.md) | Installation, capabilities, supported DBMSs, injection techniques, and core usage |
| 02 | [SQLMap Output Description](./02.%20SQLMap%20Output%20Description.md) | Understanding detection messages, DBMS fingerprinting, injection confirmation, and logs |
| 03 | [SQLMap Requests & Input Handling](./03.SQLMap%20Requests%20%26%20Input%20Handling%20(CPTS%20Notes).md) | GET/POST requests, cookies, headers, request files, JSON/XML, and injection markers |

---

## 🧰 01. SQLMap Overview

**[SQLMap Overview](./01.%20SQLMap%20Overview.md)** introduces SQLMap as a Python-based framework for automating SQL Injection detection and exploitation.

### Core Capabilities

- Connection testing
- Injection detection
- DBMS fingerprinting
- Database enumeration
- Data extraction
- File-system operations where supported
- OS-command functionality where supported
- WAF/IDS-related testing features

### Injection Techniques

| Code | Technique |
|---|---|
| B | Boolean-based blind |
| E | Error-based |
| U | UNION-based |
| S | Stacked queries |
| T | Time-based blind |
| Q | Inline queries |

The notes explain that different techniques have different speed, visibility, and response requirements.

### Basic Usage

```bash
sqlmap -u 'http://target/page.php?id=5'
```

---

## 📊 02. SQLMap Output Description

**[SQLMap Output Description](./02.%20SQLMap%20Output%20Description.md)** is a quick-reference guide for interpreting SQLMap's terminal output.

### Important Messages

| SQLMap Message | Meaning |
|---|---|
| URL content is stable | Baseline responses are consistent |
| Parameter appears dynamic | Parameter changes application behavior |
| Might be injectable | Initial SQLi indication |
| Back-end DBMS identified | DBMS fingerprint completed |
| Reflective values found | Reflected payload noise detected |
| Appears injectable | Strong evidence for a technique |
| Parameter is vulnerable | Vulnerability confirmed |
| Injection points identified | SQLMap identified usable injection points |
| Data logged | Results stored in SQLMap output directory |

### Detection Flow

```text
Connection
   ↓
Response Stability
   ↓
Dynamic Parameter
   ↓
Heuristic Testing
   ↓
Technique Testing
   ↓
DBMS Fingerprinting
   ↓
Injection Confirmation
```

The notes emphasize that early heuristic messages are indicators, while later messages provide stronger confirmation.

---

## 📨 03. SQLMap Requests & Input Handling

**[SQLMap Requests & Input Handling](./03.SQLMap%20Requests%20%26%20Input%20Handling%20(CPTS%20Notes).md)** explains why a correct request is often more important than simply running SQLMap.

### GET Parameters

```bash
sqlmap -u "http://target.com/?id=1"
```

### POST Parameters

```bash
sqlmap -u "http://target.com/" --data="uid=1&name=test"
```

### Specific Parameter

```bash
sqlmap -u "http://target.com/" --data="uid=1&name=test" -p uid
```

### Injection Marker

An asterisk can identify the exact injection point:

```bash
sqlmap -u "http://target.com/" --data="uid=1*&name=test"
```

---

## 📄 Using Full HTTP Requests

For applications with authentication, custom headers, or complex bodies, the notes recommend saving the complete HTTP request.

Example:

```bash
sqlmap -r req.txt
```

This preserves context such as:

- Cookies
- Headers
- POST data
- Authentication tokens
- JSON/XML bodies
- Custom request fields

The notes describe obtaining a request from browser developer tools or an HTTP proxy and using it as SQLMap input.

---

## 🍪 Cookies & Authentication

Authenticated application functionality may require a valid session cookie.

The notes cover supplying cookies directly:

```bash
sqlmap --cookie="PHPSESSID=<SESSION>"
```

This is important when the vulnerable parameter is only reachable after login.

---

## 🧾 Custom Headers

SQLMap can also receive application-specific headers using `-H`.

The source notes reference headers such as:

- Cookie
- Referer
- Host
- User-Agent
- X-Forwarded-For

Header-based injection positions can also be tested when the application processes header values.

---

## 🎭 User-Agent Handling

The notes cover:

```bash
--random-agent
```

for changing the HTTP User-Agent used by SQLMap.

They also reference:

```bash
--mobile
```

when testing application behavior intended for mobile clients.

---

## 🔄 HTTP Methods

Modern applications may use methods beyond GET and POST.

The module covers:

- GET
- POST
- PUT
- PATCH
- DELETE

SQLMap can be supplied with an alternate method when required by the application.

---

## 🧩 JSON & XML Requests

Modern APIs frequently place parameters inside structured request bodies.

### JSON Example

```json
{
  "id": 1,
  "name": "admin"
}
```

SQLMap can process JSON data supplied through `--data` or through a complete request file.

### XML Example

```xml
<user>
    <id>1</id>
</user>
```

The notes also recommend using full request files for complex API traffic.

---

## 🧭 SQLMap Workflow

A practical workflow based on the module:

```text
Capture Working Request
        ↓
Identify Candidate Parameters
        ↓
Preserve Cookies / Headers
        ↓
Run SQLMap
        ↓
Review Detection Output
        ↓
Identify DBMS
        ↓
Confirm Injection Point
        ↓
Select Appropriate Enumeration
        ↓
Document Results
```

---

## 🧠 Key Learning Points

### Request Fidelity Matters

An incomplete request can make a vulnerable parameter appear non-vulnerable.

### Understand SQLMap Output

The distinction between **"might be injectable"** and **"parameter is vulnerable"** is important.

### DBMS Fingerprinting Improves Accuracy

Once the backend DBMS is identified, SQLMap can use appropriate DBMS-specific techniques.

### Complex Requests Need `-r`

Cookies, custom headers, JSON, authentication, and non-standard methods are easier to reproduce using a saved raw request.

### SQLMap Is an Automation Tool

Automated detection should still be reviewed and validated rather than accepted blindly.

---

## 🛠️ Tools Covered

**SQLMap · Burp Suite / Browser Developer Tools · cURL · HTTP Requests**

---

## 📖 Recommended Study Order

**1. SQLMap Overview → 2. Injection Techniques → 3. SQLMap Output → 4. GET/POST Requests → 5. Cookies & Headers → 6. Full Request Files → 7. JSON/XML/API Requests**

---

## ⚠️ Responsible Use

Use SQLMap only against:

**HTB Academy · CPTS Labs · CTFs · Authorized Applications**

Automated SQL Injection testing can generate significant traffic and may modify or extract data. Keep all testing within the approved scope.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Web Application Security | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for SQLMap detection, request handling, injection techniques, and result analysis.**
