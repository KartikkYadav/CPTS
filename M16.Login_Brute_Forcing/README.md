# Login Brute Forcing - HTB Academy Guide

A practical CPTS module covering **brute-force mathematics, dictionary attacks, Hydra, Medusa, Basic HTTP Authentication, web login forms, custom wordlists, and web-service credential testing**.

---

## 📚 Module Overview

**Brute Force Fundamentals → Dictionary Attacks → Hydra → HTTP Authentication → Login Forms → Medusa → Web Services → Custom Wordlists**

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [Brute Force Attacks](./01.Brute%20Force%20Attacks.md) | Password search space, complexity, PIN brute forcing, and automation |
| 02 | [Dictionary Attacks](./02.%20Dictionary%20Attacks.md) | Wordlists, targeted password guessing, and brute-force comparison |
| 03 | [Hydra](./03.Hydra.md) | Network login cracking, modules, options, and service-specific attacks |
| 04 | [Basic HTTP Authentication](./04.Basic%20HTTP%20Authentication.md) | Basic Auth flow, Base64 credentials, Hydra, and authentication weaknesses |
| 05 | [Login Forms](./05.Login%20Forms.md) | HTML forms, POST requests, Hydra http-post-form, and success/failure conditions |
| 06 | [Medusa](./06.Medusa.md) | Parallel login testing and service modules |
| 07 | [Web Services Brute Forcing](./07.%20Web%20Services%20Brute%20Forcin.md) | SSH/FTP authentication attacks using Medusa |
| 07A | [Custom Wordlists](./07.Custom%20Wordlists.md) | Username Anarchy, CUPP, OSINT-driven lists, and password filtering |

---

## 🔢 01. Brute Force Attacks

**[Brute Force Attacks](./01.Brute%20Force%20Attacks.md)** explains why password length and character-set size have an exponential effect on the search space.

### Formula

```text
Possible Combinations = Character Set Size ^ Password Length
```

The notes use examples involving:

- Lowercase characters
- Uppercase + lowercase
- Digits
- Symbols
- Different password lengths

The section also includes a practical four-digit PIN lab and an automated Python request loop.

---

## 📚 02. Dictionary Attacks

**[Dictionary Attacks](./02.%20Dictionary%20Attacks.md)** focuses on testing a predefined list of likely passwords instead of generating every possible combination.

### Core Idea

Dictionary attacks exploit **human predictability**.

Targeted wordlists can include terms related to:

- Organization names
- Employee names
- Departments
- Hobbies
- Industry terminology
- Common password patterns

### Brute Force vs Dictionary

| Feature | Brute Force | Dictionary |
|---|---|---|
| Candidate generation | Every combination | Predefined candidates |
| Search space | Very large | Smaller |
| Speed | Often slower | Usually faster |
| Targeting | Limited | Highly targetable |
| Best Against | Unknown/complex patterns | Common/predictable passwords |

---

## ⚡ 03. Hydra

**[Hydra](./03.Hydra.md)** covers Hydra as a parallelized network login-cracking tool.

### Common Options

| Option | Purpose |
|---|---|
| `-l` | Single username |
| `-L` | Username list |
| `-p` | Single password |
| `-P` | Password list |
| `-t` | Parallel tasks |
| `-f` | Stop after success |
| `-s` | Custom port |
| `-v/-V` | Verbose output |

### Services Covered

**FTP · SSH · HTTP · SMTP · POP3 · IMAP · MySQL · MSSQL · VNC · RDP**

The notes emphasize service-specific modules and adjusting concurrency to avoid excessive connection pressure.

---

## 🌐 04. Basic HTTP Authentication

**[Basic HTTP Authentication](./04.Basic%20HTTP%20Authentication.md)** explains the Basic Auth process.

### Authentication Flow

```text
Request Protected Resource
       ↓
401 Unauthorized
       ↓
WWW-Authenticate
       ↓
Username + Password
       ↓
Base64(username:password)
       ↓
Authorization Header
```

Example:

```http
Authorization: Basic <encoded-value>
```

### Important Security Point

**Base64 is encoding, not encryption.**

Basic authentication therefore requires HTTPS/TLS to protect credentials in transit.

The notes also demonstrate Hydra's `http-get` module for testing Basic Auth in the lab.

---

## 📝 05. Login Forms

**[Login Forms](./05.Login%20Forms.md)** covers modern HTML-based login forms.

### Typical Request

```http
POST /login HTTP/1.1
Content-Type: application/x-www-form-urlencoded

username=user&password=password
```

### Hydra

The module introduces:

```text
http-post-form
```

Hydra uses the login path, parameters, and a success/failure condition to automate authentication testing.

### Important Concept

Hydra needs a reliable response indicator to distinguish:

**Valid Credentials vs Invalid Credentials**

This can be based on:

- Response text
- HTTP status
- Redirect behavior
- Other response differences

---

## 🧰 06. Medusa

**[Medusa](./06.Medusa.md)** covers another modular, parallel login-testing tool.

### Main Syntax

```bash
medusa [target options] [credential options] -M module [module options]
```

### Common Options

- `-h` → host
- `-H` → target list
- `-u` → username
- `-U` → username list
- `-p` → password
- `-P` → password list
- `-M` → service module
- `-t` → parallel tasks
- `-n` → custom port
- `-f/-F` → stop conditions

### Common Modules

**ssh · ftp · http · imap · mysql · pop3 · rdp · telnet · vnc · web-form**

---

## 🌍 07. Web Services Brute Forcing

**[Web Services Brute Forcing](./07.%20Web%20Services%20Brute%20Forcin.md)** demonstrates how authentication weaknesses across common services can be chained.

The notes use:

**SSH → Initial Access → Local Enumeration → FTP Discovery → FTP Credential Testing**

This reinforces the principle that gaining access to one service can reveal additional internal attack surfaces.

---

## 🧠 07A. Custom Wordlists

**[Custom Wordlists](./07.Custom%20Wordlists.md)** focuses on creating targeted username and password lists.

### Username Anarchy

Generates realistic username variants from personal names and naming conventions.

### CUPP

Generates personalized password lists using information about the target.

### OSINT Inputs

The notes reference information such as:

- Names
- Nicknames
- Birth dates
- Pets
- Relationships
- Companies
- Interests
- Locations

### Password Policy Filtering

The source also demonstrates filtering a large list to match known password requirements, reducing unnecessary login attempts.

---

## 🧭 Authentication Attack Workflow

```text
Identify Authentication Service
          ↓
Determine Authentication Type
          ↓
Collect / Build Username List
          ↓
Collect / Build Password List
          ↓
Respect Lockout / Rate Limits
          ↓
Run Controlled Credential Testing
          ↓
Validate Successful Authentication
          ↓
Document Evidence
```

---

## 🎯 Tool Selection

| Scenario | Tool / Technique |
|---|---|
| Small PIN space | Scripted requests |
| Common passwords | Dictionary attack |
| Network login service | Hydra / Medusa |
| Basic HTTP Auth | Hydra http-get |
| Web login form | Hydra http-post-form |
| Custom username patterns | Username Anarchy |
| Targeted password generation | CUPP |
| Large-scale controlled testing | Hydra / Medusa with tuned concurrency |

---

## 🧠 Key Learning Points

### Password Length Has the Largest Effect

The search space grows exponentially as password length increases.

### Wordlists Must Be Relevant

A targeted list can be much more effective than a huge generic list when users choose predictable passwords.

### Online Attacks Are Noisy

Failed login attempts can trigger:

- Account lockouts
- Rate limiting
- Detection
- Alerts
- Service instability

### Response Analysis Matters

For web login attacks, the important task is not only sending credentials but correctly determining whether authentication succeeded.

### Custom Wordlists Reduce Noise

Username and password lists tailored to the environment reduce unnecessary guesses and improve testing efficiency.

---

## 🛠️ Tools Covered

**Hydra · Medusa · Python · SecLists · CeWL · Username Anarchy · CUPP**

---

## 📖 Recommended Study Order

**1. Brute Force → 2. Dictionary Attacks → 3. Hydra → 4. Basic Auth → 5. Login Forms → 6. Medusa → 7. Web Services → 8. Custom Wordlists**

---

## ⚠️ Responsible Use

Use authentication-testing techniques only against:

**HTB Academy · CPTS Labs · CTFs · Security Labs · Authorized Assessments**

Respect account lockout thresholds, rate limits, service stability, and engagement rules. Do not test credentials against accounts or systems without authorization.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for brute-force fundamentals, dictionary attacks, Hydra, Medusa, web authentication, and targeted wordlists.**
