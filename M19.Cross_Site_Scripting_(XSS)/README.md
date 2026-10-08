# Cross-Site Scripting (XSS) - HTB Academy Guide

A practical CPTS module covering **XSS fundamentals, Stored XSS, Reflected XSS, DOM-based XSS, discovery, stored-XSS defacement, XSS phishing, session hijacking, and prevention**.

---

## 📚 Module Overview

**XSS Fundamentals → Stored → Reflected → DOM-Based → Discovery → Impact → Session Hijacking → Prevention**

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [XSS Fundamentals](./01.XSS%20(Cross-Site%20Scripting)%20.md) | XSS architecture, client-side execution, impact, and common vectors |
| 02 | [Stored XSS](./02.Stored%20XSS.md) | Persistent payloads, database storage, and victim-side execution |
| 03 | [Reflected XSS](./03.%20Reflected%20XSS.md) | Non-persistent request/response reflection |
| 04 | [DOM-Based XSS](./04.DOM-Based%20XSS.md) | Client-side sources, sinks, URL fragments, and DOM manipulation |
| 05 | [XSS Discovery](./05.XSS%20Discovery.md) | Automated and manual discovery, source/sink review, and context analysis |
| 06 | [Defacing via Stored XSS](./06.%20Defacing%20via%20Stored%20XSS.md) | DOM manipulation and visual page changes |
| 07 | [XSS Phishing Attack](./07.XSS%20Phishing%20Attack.md) | Fake login forms and XSS-based phishing simulation |
| 08 | [Session Hijacking](./08.Session%20Hijacking.md) | Blind XSS, cookie theft, and session-abuse concepts |
| 09 | [XSS Prevention](./09.%20XSS%20Prevention.md) | Input validation, sanitization, output encoding, and safer DOM usage |

---

## 💉 01. XSS Fundamentals

**[XSS Fundamentals](./01.XSS%20(Cross-Site%20Scripting)%20.md)** introduces Cross-Site Scripting as a client-side vulnerability where attacker-controlled JavaScript can execute in another user's browser.

### Core Flow

```text
Attacker Input
      ↓
Application
      ↓
Malicious Content
      ↓
Victim Browser
      ↓
JavaScript Execution
```

### Common Input Points

- Comments
- Search fields
- Contact forms
- User profiles
- Feedback forms
- Messages

### Potential Impact

The notes discuss impacts such as:

- Account actions performed in the victim's context
- Cookie/session exposure
- Unauthorized API requests
- Credential theft
- Phishing
- Content manipulation
- Browser resource abuse

---

## 💾 02. Stored XSS

**[Stored XSS](./02.Stored%20XSS.md)** is persistent XSS where the malicious input is stored by the application and later rendered to users.

### Flow

```text
Attacker Input
      ↓
Application Storage
      ↓
Victim Requests Page
      ↓
Stored Payload Returned
      ↓
Browser Executes
```

The notes use a stored to-do-list example and emphasize that victims do not need to click a specially crafted URL; simply loading the affected page can trigger the stored payload.

---

## 🔗 03. Reflected XSS

**[Reflected XSS](./03.%20Reflected%20XSS.md)** is non-persistent XSS where attacker-controlled input is immediately reflected in the application's response.

### Flow

```text
Crafted Input
     ↓
HTTP Request
     ↓
Server
     ↓
HTTP Response
     ↓
Browser
     ↓
JavaScript Execution
```

Unlike Stored XSS, the payload is not permanently stored by the application.

---

## 🧩 04. DOM-Based XSS

**[DOM-Based XSS](./04.DOM-Based%20XSS.md)** focuses on vulnerabilities where client-side JavaScript reads attacker-controlled data and modifies the DOM unsafely.

### Key Difference

| Type | Server Processing | Persistence |
|---|---|---|
| Stored XSS | Yes | Yes |
| Reflected XSS | Yes | No |
| DOM XSS | No | No |

The notes emphasize URL fragments such as:

```text
#task=test
```

because fragments are processed by the browser and are not normally sent to the server.

### Source vs Sink

```text
Attacker-Controlled Source
          ↓
      JavaScript
          ↓
      Unsafe Sink
          ↓
      DOM Update
          ↓
     Script Execution
```

---

## 🔎 05. XSS Discovery

**[XSS Discovery](./05.XSS%20Discovery.md)** covers automated and manual discovery.

### Automated Tools

The notes reference:

**Burp Suite Professional · Nessus · OWASP ZAP · XSStrike · BruteXSS · XSSer**

### Manual Testing

The notes demonstrate using simple payloads to identify whether input is:

- Reflected
- Stored
- Executed
- Escaped
- Sanitized

### Context Matters

An input may appear inside:

- HTML
- An attribute
- JavaScript
- CSS

A payload that works in one context may fail in another.

### Source/Sink Review

The notes highlight client-side sources such as:

```javascript
location.search
location.hash
document.URL
document.cookie
```

and potentially dangerous DOM sinks such as:

```javascript
innerHTML
outerHTML
document.write()
```

---

## 🎨 06. Defacing via Stored XSS

**[Defacing via Stored XSS](./06.%20Defacing%20via%20Stored%20XSS.md)** demonstrates how stored JavaScript can modify visible page elements.

The notes discuss manipulating:

- Background color
- Background image
- Page title
- Page content

### Concept

```text
Stored XSS
   ↓
JavaScript Execution
   ↓
DOM Manipulation
   ↓
Page Appearance Changes
```

The impact is not limited to appearance; successful stored XSS can affect user trust and application behavior depending on where it executes.

---

## 🎣 07. XSS Phishing Attack

**[XSS Phishing Attack](./07.XSS%20Phishing%20Attack.md)** covers using XSS to inject a fake authentication interface in a controlled phishing-simulation scenario.

The notes describe:

- Identifying an XSS point
- Injecting HTML
- Creating a fake login form
- Sending submitted data to a controlled server
- Removing the original form to make the injected page appear more believable

This material is especially relevant for authorized awareness exercises and security assessments.

---

## 🍪 08. Session Hijacking

**[Session Hijacking](./08.Session%20Hijacking.md)** explains how browser cookies may be targeted when JavaScript can execute in a user's context.

### Blind XSS

Blind XSS occurs when the vulnerable content is later viewed by a privileged user such as an administrator.

The notes describe using callback requests to determine:

- Which field is vulnerable
- Whether the payload executed

### Conceptual Flow

```text
User Input
     ↓
Stored / Deferred Processing
     ↓
Privileged User Views Data
     ↓
XSS Executes
     ↓
Controlled Callback
     ↓
Security Impact Analysis
```

The section connects JavaScript execution with possible session-cookie exposure.

---

## 🛡️ 09. XSS Prevention

**[XSS Prevention](./09.%20XSS%20Prevention.md)** covers the defensive side of XSS.

### Core Defenses

- Input validation
- Input sanitization
- Output encoding
- Secure server-side processing
- Safer DOM manipulation
- Secure server configuration

### Important Principle

Security controls must exist on the **server side**, because client-side validation can be bypassed using custom HTTP requests.

### Dangerous DOM APIs

The source notes specifically call out unsafe use of:

```javascript
innerHTML
outerHTML
document.write()
document.writeln()
```

and similar HTML-insertion functions.

### jQuery

Care is also required with functions such as:

```text
html()
append()
prepend()
before()
after()
replaceWith()
```

when handling untrusted input.

---

## 🧭 XSS Testing Workflow

```text
1. Identify User-Controlled Input
           ↓
2. Determine Reflection / Storage
           ↓
3. Identify Injection Context
           ↓
4. Test Safely
           ↓
5. Confirm JavaScript Execution
           ↓
6. Determine XSS Type
           ↓
7. Assess Affected Users / Privileges
           ↓
8. Document Impact
           ↓
9. Recommend Mitigation
```

---

## 🧠 Stored vs Reflected vs DOM XSS

| Type | Stored? | Server Involved? | Trigger |
|---|---|---|---|
| Stored | Yes | Yes | Victim loads affected content |
| Reflected | No | Yes | Victim follows/sends crafted request |
| DOM | No | No | Client-side JavaScript processes attacker-controlled input |

---

## 🧠 Key Learning Points

### XSS Is Context-Dependent

The correct test depends on where input lands in the application.

### Reflection Does Not Always Mean XSS

A value can be reflected safely through encoding. Execution must be demonstrated.

### DOM XSS Requires Client-Side Analysis

Inspect JavaScript sources and sinks, not just server responses.

### Stored XSS Has Broad Reach

A persistent payload may execute for many users who visit the affected content.

### Prevention Must Be Server-Side

Client-side restrictions can be modified or bypassed, so important validation and output handling must happen securely on the backend as well.

---

## 🛠️ Tools Covered

**Burp Suite · OWASP ZAP · XSStrike · Nessus · XSSer · BruteXSS · Browser Developer Tools**

---

## 📖 Recommended Study Order

**1. XSS Fundamentals → 2. Stored XSS → 3. Reflected XSS → 4. DOM XSS → 5. Discovery → 6. Defacement → 7. Phishing Simulation → 8. Session Hijacking → 9. Prevention**

---

## ⚠️ Responsible Use

Use XSS techniques only for:

**HTB Academy · CPTS Preparation · CTFs · Security Labs · Authorized Web Applications**

Do not use session theft, phishing, or stored-XSS techniques against real users without explicit authorization. Treat collected cookies and personal data as sensitive.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Web Application Security | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for XSS discovery, exploitation concepts, browser-side impact, and secure prevention.**
