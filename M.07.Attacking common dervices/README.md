# Attacking Common Services - HTB Academy Guide

A practical CPTS module covering attacks against **FTP, SQL/MSSQL, RDP, DNS, and vulnerable subdomain configurations**, with emphasis on service enumeration, authentication weaknesses, protocol abuse, and controlled exploitation.

The notes focus on turning service discoveries into validated attack paths and understanding how common enterprise services can be abused when they are weakly configured or vulnerable.

---

## 📚 Module Overview

**Service Enumeration → Authentication Testing → Misconfiguration Discovery → Vulnerability Research → Controlled Exploitation → Credential / Access Validation**

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [FTP Attack](./01.FTP_attack.md) | FTP enumeration, anonymous access, credential discovery, Hydra, and flag retrieval |
| 02 | [Latest FTP Vulnerabilities](./02.Latest%20FTP%20Vulnerabilities.md) | CoreFTP path traversal and arbitrary file-write vulnerability |
| 03 | [Attacking SQL Databases](./03.Attacking%20SQL%20Databases.md) | FTP, SMB and MSSQL attack chains, credential discovery, and SSH access |
| 04 | [SQL Vulnerabilities](./04.SQL%20Vulnerabilities.md) | MSSQL `xp_dirtree`, NTLMv2 capture, SMB relay concepts, and credential reuse |
| 05 | [Attacking RDP](./05.ttacking%20RDP%20(Remote%20Desktop%20Protocol).md) | RDP enumeration, password spraying, session hijacking, and Pass-the-Hash concepts |
| 06 | [Latest RDP Vulnerabilities](./06.%20Latest%20RDP%20Vulnerabilities.md) | BlueKeep / CVE-2019-0708, memory corruption, RCE, and operational risk |
| 07 | [Attacking DNS](./07.Attacking%20DNS%20(Domain%20Name%20System).md) | DNS enumeration, AXFR, subdomain takeover, and local DNS spoofing concepts |
| 08 | [Subdomain Takeover](./08.Subdomain%20Takeover.md) | Dangling CNAME records, third-party resource claims, and attack impact |

---

## 📁 01. FTP Attack

**[FTP Attack](./01.FTP_attack.md)** demonstrates a service-attack workflow beginning with enumeration.

### Main Flow

**Nmap → Non-Standard FTP Port → Anonymous Access → Username/Password Discovery → Credential Testing → Authenticated FTP → Flag Retrieval**

The notes demonstrate how publicly accessible FTP files can reveal user/password lists and create a path toward authenticated access.

### Key Lessons

- FTP may operate on a non-standard port
- Anonymous access should always be checked during authorized testing
- Exposed credential material can become the bridge to authentication
- Weak credentials can make a service directly compromiseable

---

## 🚨 02. Latest FTP Vulnerabilities

**[Latest FTP Vulnerabilities](./02.Latest%20FTP%20Vulnerabilities.md)** covers a CoreFTP vulnerability assigned **CVE-2022-22836**.

The source notes describe an authenticated directory-traversal and arbitrary-file-write condition involving HTTP PUT handling.

### Vulnerability Chain

**Authenticated Request → Path Traversal → Escape Restricted Directory → Arbitrary File Write**

Example request structure from the notes:

```text
HTTP PUT
   ↓
Path traversal
   ↓
Restricted path bypass
   ↓
File write outside intended directory
```

The notes break the vulnerability down into:

- Source
- Process
- Privileges
- Destination

This framework helps connect user-controlled input to the eventual security impact.

---

## 🗄️ 03. Attacking SQL Databases

**[Attacking SQL Databases](./03.Attacking%20SQL%20Databases.md)** demonstrates a multi-service attack path involving **FTP, SMB, SSH, and database credentials**.

### FTP Path

The notes show:

**Nmap → Anonymous FTP → Credential Files → Credential Discovery → Hydra → Authenticated FTP**

### SMB Path

The notes then demonstrate:

**SMB Enumeration → Share Discovery → Permission Analysis → Credentialed Access → SSH Key Discovery**

An important lesson is the difference between:

**Share-level permissions ≠ File-level permissions**

### SSH

The discovered private key is used after correcting its permissions:

```bash
chmod 600 id_rsa
```

The notes use this as an example of how service-to-service findings can be chained.

---

## 🔑 04. SQL Vulnerabilities

**[SQL Vulnerabilities](./04.SQL%20Vulnerabilities.md)** focuses on the MSSQL `xp_dirtree` function and how it can trigger outbound SMB authentication.

### Core Concept

```text
MSSQL
  ↓
xp_dirtree
  ↓
SMB connection
  ↓
NTLMv2 authentication
  ↓
Capture / Analysis
  ↓
Cracking or Relay
```

The source notes explain that `xp_dirtree` itself is not simply a vulnerability; the attack path abuses how SMB authentication can occur when MSSQL accesses a network resource.

### Potential Impact

The notes discuss:

- NTLMv2 hash capture
- Offline cracking
- SMB relay
- Credential reuse
- Lateral movement

The material also references additional MSSQL abuse possibilities such as command execution, extended stored procedures, and linked-server abuse.

---

## 🖥️ 05. Attacking RDP

**[Attacking RDP](./05.ttacking%20RDP%20(Remote%20Desktop%20Protocol).md)** covers RDP as a major Windows remote-administration attack surface.

### Enumeration

```bash
nmap -Pn -p3389 <IP>
```

### Authentication Attacks

The notes cover:

- Password guessing
- Account lockout considerations
- Password spraying
- Crowbar
- Hydra

The source emphasizes limiting attempts because excessive authentication failures may trigger lockouts, alerts, or service instability.

### RDP Access

Tools referenced:

**rdesktop · xfreerdp**

### Session Hijacking

The notes describe:

- Enumerating logged-in users with `query user`
- Identifying session IDs
- SYSTEM-level requirements
- `tscon.exe`
- Creating a service to execute `tscon`

### Pass-the-Hash

The section introduces RDP authentication using an NTLM hash when the target is configured for **Restricted Admin Mode**.

---

## 🔥 06. Latest RDP Vulnerabilities

**[Latest RDP Vulnerabilities](./06.%20Latest%20RDP%20Vulnerabilities.md)** covers **BlueKeep (CVE-2019-0708)**.

### Core Characteristics

- RDP service vulnerability
- Remote code execution
- No prior authentication required
- Kernel-level memory corruption
- Use-after-free condition

### Conceptual Attack Flow

```text
Manipulated RDP Request
        ↓
Vulnerable Virtual Channel
        ↓
Kernel Memory Corruption
        ↓
Controlled Execution
        ↓
LocalSystem Context
        ↓
Remote Code Execution
```

### Operational Warning

The notes strongly emphasize that exploitation can crash vulnerable systems because the vulnerability operates at the Windows kernel level.

BlueKeep exploitation should therefore be treated as a **high-risk action requiring explicit authorization and careful coordination**, especially in production environments.

---

## 🌐 07. Attacking DNS

**[Attacking DNS](./07.Attacking%20DNS%20(Domain%20Name%20System).md)** covers DNS as both an infrastructure service and an attack surface.

### Enumeration

The notes cover:

- UDP/TCP 53
- Nmap DNS fingerprinting
- DNS records
- AXFR
- Subdomain discovery

Example:

```bash
nmap -p53 -Pn -sV -sC <IP>
```

### Zone Transfer

```bash
dig AXFR @<nameserver> <domain>
```

A misconfigured AXFR can expose a complete DNS zone.

### Subdomain Takeover

The notes explain the common pattern:

**CNAME → Third-Party Resource → Resource Removed → DNS Record Left Behind → Dangling Subdomain**

### DNS Spoofing

The source also introduces local DNS spoofing and cache-poisoning concepts using tools such as:

**Ettercap · Bettercap**

The focus is on understanding how DNS resolution can be manipulated in an authorized local lab.

---

## 🏴 08. Subdomain Takeover

**[Subdomain Takeover](./08.Subdomain%20Takeover.md)** provides a dedicated explanation of dangling DNS records.

### Technical Triangle

A likely takeover condition exists when:

1. A company subdomain points to a third-party service using a CNAME.
2. The external resource no longer exists.
3. The third-party platform allows that resource name to be registered again.

### Attack Impact

The notes discuss potential consequences including:

- Phishing
- Session-cookie exposure in poorly scoped deployments
- CSRF implications
- CORS abuse
- CSP trust relationships

The practical lesson is that **DNS lifecycle management is part of application security**.

---

## 🧭 Service Attack Workflow

A practical workflow across this module is:

```text
1. Identify Service
        ↓
2. Enumerate Version / Configuration
        ↓
3. Check Authentication
        ↓
4. Test Misconfigurations
        ↓
5. Research Known Vulnerabilities
        ↓
6. Validate Impact
        ↓
7. Obtain Authorized Access / Proof
        ↓
8. Document Attack Path
```

---

## 🔗 Common Attack Chains

### FTP

```text
Anonymous Access
→ Credential Files
→ Credential Testing
→ Authenticated Access
```

### SMB

```text
Share Enumeration
→ Permissions
→ Sensitive File
→ Credential / Key
→ SSH
```

### MSSQL

```text
MSSQL Interaction
→ SMB Authentication
→ NTLMv2 Capture
→ Crack / Relay
→ Lateral Movement
```

### RDP

```text
RDP Enumeration
→ Credential Testing
→ Valid Account
→ Remote Session
```

### DNS

```text
DNS Enumeration
→ CNAME Discovery
→ Orphaned Third-Party Resource
→ Potential Subdomain Takeover
```

---

## 🧠 Key Learning Points

### Enumeration Drives Exploitation

The module repeatedly begins with identifying the service and understanding its configuration before attempting an attack.

### Misconfigurations Matter

Anonymous FTP, exposed SMB resources, weak credentials, insecure DNS records, and poor service configuration can create serious attack paths without requiring a new CVE.

### Attack Chains Are More Important Than Isolated Findings

A credential discovered through one service may unlock another service. This is demonstrated repeatedly across FTP, SMB, SSH, RDP, and MSSQL.

### High-Risk Exploits Need Operational Awareness

Kernel-level vulnerabilities such as BlueKeep can cause system instability. Exploitation should never be treated as a routine scan.

---

## 🛠️ Tools Covered

**Nmap · FTP · Hydra · smbclient · smbmap · rpcclient · Metasploit · cURL · Responder · Wireshark · tcpdump · Crowbar · rdesktop · xfreerdp · dig · host · nslookup · Ettercap · Bettercap**

---

## 📖 Recommended Study Order

**1. FTP Attack → 2. FTP Vulnerabilities → 3. SQL/SMB Attack Chains → 4. MSSQL Abuse → 5. RDP → 6. RDP Vulnerabilities → 7. DNS → 8. Subdomain Takeover**

---

## ⚠️ Responsible Use

Use these techniques only for:

**HTB Academy · CPTS Preparation · CTFs · Security Labs · Authorized Penetration Testing**

Password attacks, credential interception, relay techniques, session hijacking, DNS spoofing, and remote-code-execution exploits can affect systems and users. Perform them only within a clearly defined and authorized scope.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for attacking common services, validating service weaknesses, and understanding multi-stage attack paths.**
