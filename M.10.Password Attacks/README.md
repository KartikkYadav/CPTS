# Password Attacks - HTB Academy Guide

A practical CPTS module covering **password cracking fundamentals, John the Ripper, Hashcat, custom wordlists and rules, protected-file cracking, network-service attacks, password spraying, credential hunting, and password managers**.

The module focuses on understanding how password weaknesses arise, how credentials can be recovered or discovered during authorized assessments, and how password-related findings can connect different parts of an environment.

---

## 📚 Module Overview

**Password Fundamentals → Hash Identification → John the Ripper → Hashcat → Custom Wordlists/Rules → Protected Files → Network Services → Password Spraying → Credential Hunting → Password Managers**

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [Introduction to Password Cracking](./01.Introduction%20to%20Password%20Cracking.md) | Hashes, rainbow tables, salting, brute force, and dictionary attacks |
| 02 | [John the Ripper](./02.Introduction%20to%20John%20The%20Ripper.md) | Single, wordlist, incremental, rules, hash formats, and protected-file cracking |
| 03 | [Hashcat](./03.Introduction%20to%20Hashcat.md) | Attack modes, hash modes, wordlists, rules, and mask attacks |
| 04 | [Custom Wordlists & Rules](./04.%20Writing%20Custom%20Wordlists%20and%20Ruless.md) | Targeted wordlists, Hashcat rules, mutations, and CeWL |
| 05 | [Cracking Protected Files](./05.%20Cracking%20Protected%20Files.md) | Encrypted documents, archives, SSH keys, and 2john conversion tools |
| 06 | [Cracking Protected Archives](./06.Cracking%20Protected%20Archives.md) | Password-protected archives and archive-specific cracking workflows |
| 07 | [Network Services](./07.Network%20Services.md) | Password attacks against network services |
| 08 | [Password Spraying, Credential Stuffing & Default Credentials](./08.Password%20Spraying,%20Credential%20Stuffing,%20and%20Default%20Credentials.md) | Online authentication attacks and credential reuse |
| 09 | [SAM, SYSTEM & SECURITY](./09.Attacking%20SAM,%20SYSTEM,%20and%20SECURITY.md) | Windows credential stores and password-hash acquisition concepts |
| 10 | [Linux Authentication Process](./10.inux%20Authentication%20Process.md) | Linux authentication and password-storage concepts |
| 11 | [Credential Hunting in Linux](./11.Credential%20Hunting%20in%20Linux.md) | Searching Linux systems for exposed credentials and secrets |
| 12 | [Credential Hunting in Network Traffic](./12.Credential%20Hunting%20in%20Network%20Traffic.md) | Identifying authentication material in captured network traffic |
| 13 | [Credential Hunting in Network Shares](./13.Credential%20Hunting%20in%20Network%20Shares.md) | Searching SMB/network shares for passwords and secrets |
| 14 | [Password Managers](./14.Password%20Managers.md) | Password vaults, encryption, synchronization, MFA, and passwordless authentication |

---

## 🔐 01. Password Cracking Fundamentals

**[Introduction to Password Cracking](./01.Introduction%20to%20Password%20Cracking.md)** introduces the process of recovering passwords from password hashes.

### Core Concepts

- Hashing
- MD5
- SHA-256
- Salting
- Rainbow tables
- Brute-force attacks
- Dictionary attacks
- Password wordlists

The notes emphasize that salts make precomputed rainbow-table approaches much less useful and that cracking speed depends on the hash algorithm and available hardware.

### Common Wordlists

**rockyou.txt · SecLists**

---

## 🔨 02. John the Ripper

**[John the Ripper](./02.Introduction%20to%20John%20The%20Ripper.md)** covers JtR as a password-cracking and security-auditing tool.

### Main Modes

| Mode | Purpose |
|---|---|
| Single | Uses account information and rules to generate guesses |
| Wordlist | Tests passwords from a supplied wordlist |
| Incremental | Generates guesses dynamically |

### Common Commands

```bash
john --single passwd
```

```bash
john --wordlist=rockyou.txt hashes.txt
```

```bash
john --incremental hashes.txt
```

### Hash Identification

The notes use `hashid` to identify possible hash formats and show how to force a specific JtR format when automatic detection is insufficient.

---

## ⚡ 03. Hashcat

**[Hashcat](./03.Introduction%20to%20Hashcat.md)** focuses on GPU-accelerated password recovery.

### Important Options

| Option | Purpose |
|---|---|
| `-a` | Attack mode |
| `-m` | Hash type |
| `-r` | Rule file |

### Common Hash Modes

| Hash | Mode |
|---|---:|
| MD5 | 0 |
| SHA1 | 100 |
| SHA-256 | 1400 |
| SHA-512 | 1700 |
| NTLM | 1000 |

### Dictionary Attack

```bash
hashcat -a 0 -m 0 <hash> <wordlist>
```

### Rule-Based Attack

```bash
hashcat -a 0 -m 0 <hash> rockyou.txt -r /usr/share/hashcat/rules/best64.rule
```

### Mask Attack

The notes introduce built-in character sets:

| Mask | Meaning |
|---|---|
| `?l` | Lowercase |
| `?u` | Uppercase |
| `?d` | Digits |
| `?s` | Symbols |
| `?a` | All characters |

---

## 📝 04. Custom Wordlists & Rules

**[Custom Wordlists & Rules](./04.%20Writing%20Custom%20Wordlists%20and%20Ruless.md)** focuses on targeted password generation.

### Common User Patterns

The notes highlight predictable transformations such as:

**Password → Password123 → Password2022! → P@ssw0rd2022!**

### Targeted Wordlists

OSINT can provide words related to:

- Companies
- Names
- Pets
- Hobbies
- Sports
- Locations
- Dates

### Hashcat Rules

The notes cover rules such as:

- Lowercase
- Uppercase
- Capitalization
- Character replacement
- Appending symbols

Generate a transformed list:

```bash
hashcat --force password.list -r custom.rule --stdout | sort -u > mut_password.list
```

### CeWL

CeWL is used in the notes to extract words from a website for targeted wordlists.

---

## 📦 05. Cracking Protected Files

**[Cracking Protected Files](./05.%20Cracking%20Protected%20Files.md)** covers password-protected documents and encrypted files.

### Common Targets

- PDFs
- Office documents
- ZIP/RAR archives
- SSH private keys

### SSH-Key Hunting

The notes show content-based searching for private keys because they may not have standard file extensions.

### 2john Utilities

John includes conversion tools such as:

| Utility | Target |
|---|---|
| `ssh2john` | SSH keys |
| `zip2john` | ZIP |
| `rar2john` | RAR |
| `office2john` | Office documents |
| `pdf2john` | PDFs |
| `keepass2john` | KeePass databases |
| `bitlocker2john` | BitLocker |

Typical workflow:

**Protected File → Extract Crackable Hash → JtR/Hashcat → Recover Password → Validate Access**

---

## 🗜️ 06. Cracking Protected Archives

**[Cracking Protected Archives](./06.Cracking%20Protected%20Archives.md)** provides archive-focused password-recovery material and builds on the file-cracking concepts introduced earlier.

The section is useful for understanding how encrypted archives can become an additional credential or data-discovery target during post-compromise assessment.

---

## 🌐 07. Network Services

**[Network Services](./07.Network%20Services.md)** covers password attacks against services exposed over the network.

The key concept is distinguishing:

**Offline cracking** from **online authentication attacks**.

Online attacks must account for:

- Account lockout
- Rate limiting
- Detection
- Service stability
- Authentication controls

---

## 🎯 08. Password Spraying, Credential Stuffing & Default Credentials

**[Password Spraying, Credential Stuffing & Default Credentials](./08.Password%20Spraying,%20Credential%20Stuffing,%20and%20Default%20Credentials.md)** covers three common authentication-attack models.

### Password Spraying

One password is tested against many accounts.

### Credential Stuffing

Previously exposed username/password combinations are tested against another service.

### Default Credentials

Vendor or administrator default credentials are tested where authorized.

The key operational lesson is that online password attacks can lock accounts or trigger security controls, so testing must respect engagement limits.

---

## 🪟 09. SAM, SYSTEM & SECURITY

**[SAM, SYSTEM & SECURITY](./09.Attacking%20SAM,%20SYSTEM,%20and%20SECURITY.md)** covers Windows credential-related registry hives and the security implications of obtaining password hashes from a Windows host.

The section fits into the broader workflow:

```text
Windows Access
    ↓
Credential Store Discovery
    ↓
Hash Acquisition
    ↓
Offline Password Recovery
    ↓
Credential Reuse / Validation
```

---

## 🐧 10. Linux Authentication Process

**[Linux Authentication Process](./10.inux%20Authentication%20Process.md)** covers how authentication is handled on Linux systems.

It provides the foundation needed to understand:

- User authentication
- Password storage
- Linux credential locations
- Authentication-related artifacts

This context is important before moving into Linux credential hunting and offline password recovery.

---

## 🔎 11. Credential Hunting in Linux

**[Credential Hunting in Linux](./11.Credential%20Hunting%20in%20Linux.md)** focuses on locating passwords, tokens, configuration secrets, and other authentication material after access has been obtained.

Typical areas include:

- Configuration files
- History files
- Application data
- Credentials in scripts
- User directories
- Service configuration

The emphasis is on **finding credentials before attempting to crack everything blindly**.

---

## 🌐 12. Credential Hunting in Network Traffic

**[Credential Hunting in Network Traffic](./12.Credential%20Hunting%20in%20Network%20Traffic.md)** covers identifying authentication material in captured traffic.

The notes connect network capture with credential discovery and analysis.

The general workflow is:

**Capture Traffic → Identify Relevant Protocol → Locate Authentication Data → Extract Evidence → Validate**

Encrypted protocols reduce the visibility of plaintext credentials, making protocol and encryption analysis important.

---

## 🗂️ 13. Credential Hunting in Network Shares

**[Credential Hunting in Network Shares](./13.Credential%20Hunting%20in%20Network%20Shares.md)** covers searching SMB/network shares for sensitive material.

The notes reference tools such as:

**Snaffler · MANSPIDER · NetExec**

### Search Targets

- File names
- Passwords
- Configuration files
- Secrets
- Share contents

Conceptually:

```text
Enumerate Shares
      ↓
Access Authorized Shares
      ↓
Search Files / Contents
      ↓
Identify Credentials
      ↓
Validate and Correlate
```

---

## 🔑 14. Password Managers

**[Password Managers](./14.Password%20Managers.md)** covers password-vault concepts and modern authentication.

### Password Manager Concepts

- Master password
- Encryption
- Key derivation
- Salt
- Browser integration
- Multi-device synchronization
- 2FA

### Cloud vs Local

| Type | Main Characteristic |
|---|---|
| Cloud | Encrypted synchronization across devices |
| Local | Vault stored and controlled locally |

### Modern Authentication

The notes also cover:

- MFA
- OTP
- TOTP
- FIDO2
- Security keys
- Device-compliance approaches
- Passwordless authentication

---

## 🧭 Password-Attack Workflow

A practical workflow from the module is:

```text
Identify Authentication Material
        ↓
Determine Hash / Credential Type
        ↓
Choose Offline or Online Technique
        ↓
Build Targeted Wordlist
        ↓
Apply Rules / Masks When Appropriate
        ↓
Recover or Validate Credentials
        ↓
Check for Credential Reuse
        ↓
Document Evidence and Impact
```

---

## 🧠 Key Learning Points

### Hash Identification Comes First

Choosing the correct cracking method requires understanding what type of hash or credential material you have.

### Targeted Wordlists Beat Blind Guessing

The notes emphasize using available information to model realistic password choices.

### Offline and Online Attacks Are Different

Offline cracking works against captured hash material; online attacks interact with a live authentication service and face lockout, rate limiting, and detection.

### Credential Hunting Can Be Faster Than Cracking

A plaintext password or reusable credential found in a configuration file, history file, network share, or captured traffic may eliminate the need for password cracking.

### Credential Reuse Creates Attack Chains

A password obtained from one source may provide access to a different service, making correlation critical.

---

## 🛠️ Tools Covered

**John the Ripper · Hashcat · hashid · CeWL · SecLists · rockyou.txt · Hydra · Snaffler · MANSPIDER · NetExec**

---

## 📖 Recommended Study Order

**1. Password Cracking Fundamentals → 2. John → 3. Hashcat → 4. Custom Wordlists & Rules → 5. Protected Files/Archives → 6. Network Services → 7. Password Spraying/Credential Stuffing → 8. SAM/SYSTEM/SECURITY → 9. Linux Authentication → 10. Credential Hunting → 11. Password Managers**

---

## ⚠️ Responsible Use

Use password attacks only for:

**HTB Academy · CPTS Preparation · CTFs · Security Labs · Authorized Security Assessments**

Online authentication testing can lock accounts or trigger alerts. Credential hunting may expose sensitive personal or corporate information. Keep all testing within the authorized scope and protect recovered credentials securely.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for password cracking, credential discovery, authentication attacks, and password-security fundamentals.**
