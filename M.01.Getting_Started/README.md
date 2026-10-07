# Getting Started - HTB Academy Guide

A foundational collection of **CPTS penetration-testing notes** covering service enumeration, web enumeration, public exploit research, Metasploit, privilege escalation, and Nmap-based network enumeration.

This module focuses on the core workflow of moving from **initial discovery and enumeration** toward vulnerability validation, exploitation, and post-compromise privilege escalation in authorized labs and assessments.

---

## 📚 Module Overview

The notes in this module build a basic penetration-testing workflow:

**Service Scanning → Web Enumeration → Public Exploit Research → Exploitation → Initial Access → Privilege Escalation**

Nmap is used throughout the material as a primary network discovery and service-enumeration tool.

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [Service Scanning & Enumeration](./01.Service%20Scanning%20%26%20numeration.md) | Ports, services, banners, FTP, SMB, and SNMP enumeration |
| 02 | [Web Enumeration](./02.%20Web%20Enumeration.md) | Directory discovery, subdomains, headers, technologies, robots.txt, and source-code review |
| 03 | [Public Exploits & Metasploit](./03.Public%20Exploits%20%26%20Metasploit.md) | Searchsploit, exploit research, Metasploit, vulnerability checking, and lab exploitation |
| 04 | [Privilege Escalation](./04.Privilege%20Escalation.md) | Linux/Windows privilege escalation, enumeration, credentials, sudo, SUID, cron, and SSH keys |
| 05 | [Network Enumeration with Nmap](./05.Network%20Enumeration%20with%20Nmap.md) | Nmap architecture, scan types, host discovery, ports, services, OS detection, and NSE |

---

## 🔎 01. Service Scanning & Enumeration

**[Service Scanning & Enumeration](./01.Service%20Scanning%20%26%20numeration.md)**

Introduces service discovery and enumeration as a critical early phase of a penetration test.

### Topics Covered

- Port ranges and common ports
- Nmap basic and advanced scanning
- Service and version detection
- Banner grabbing with Netcat
- FTP enumeration
- Anonymous FTP access
- SMB enumeration
- SMB share discovery and access
- SNMP enumeration
- Community strings
- `snmpwalk`
- `onesixtyone`
- Identifying potential misconfigurations
- Chaining information from multiple services

### Core Workflow

**Nmap Scan → Service Identification → Enumeration → Misconfiguration Discovery → Credential/File Discovery → Further Testing**

---

## 🌐 02. Web Enumeration

**[Web Enumeration](./02.%20Web%20Enumeration.md)**

Focuses on discovering the externally visible structure and technologies of web applications.

### Topics Covered

- Directory and file enumeration
- DNS subdomain enumeration
- HTTP response headers
- Technology fingerprinting
- SSL/TLS certificate information
- `robots.txt`
- Source-code inspection
- Hidden directories and endpoints

### Tools Referenced

- Gobuster
- FFUF
- curl
- WhatWeb

### Core Workflow

**Identify Web Service → Enumerate Directories → Enumerate Subdomains → Inspect Headers → Fingerprint Technologies → Review robots.txt → Inspect Source**

---

## 💣 03. Public Exploits & Metasploit

**[Public Exploits & Metasploit](./03.Public%20Exploits%20%26%20Metasploit.md)**

Covers the transition from enumeration to researching known vulnerabilities and validating applicable exploits.

### Topics Covered

- Public exploit research
- Searchsploit
- Exploit-DB
- Rapid7
- Metasploit Framework
- `msfconsole`
- Exploit searching
- Required exploit options
- `check`
- `exploit`
- Meterpreter sessions
- Post-exploitation shell access

### Basic Methodology

**Enumerate → Identify Version → Search Public Exploits → Validate Applicability → Check → Exploit → Verify Access**

The accompanying lab notes reinforce an important principle: **re-evaluate obvious application clues before spending excessive time on unrelated exploit paths.**

---

## ⬆️ 04. Privilege Escalation

**[Privilege Escalation](./04.Privilege%20Escalation.md)**

Introduces post-compromise enumeration and common local privilege-escalation areas.

### Linux Focus

- Linux enumeration
- LinEnum
- LinPEAS
- Kernel vulnerabilities
- Installed software
- `sudo -l`
- SUID
- Cron jobs
- Configuration files
- Shell history
- SSH keys
- Writable locations
- GTFOBins

### Windows Focus

- Windows enumeration
- Seatbelt
- JAWS
- WinPEAS
- Token privileges
- Installed applications
- PowerShell history
- Credential discovery
- LOLBAS

### Key Principle

**Initial Access → Local Enumeration → Identify Weakness → Validate Privilege Path → Escalate**

The source notes also emphasize that automated privilege-escalation scripts can generate significant output and may trigger security controls, making manual verification important in sensitive environments.

---

## 🛰️ 05. Network Enumeration with Nmap

**[Network Enumeration with Nmap](./05.Network%20Enumeration%20with%20Nmap.md)**

Provides a dedicated introduction to Nmap and its scanning architecture.

### Nmap Capabilities

1. **Host Discovery**
2. **Port Scanning**
3. **Service Enumeration**
4. **OS Detection**
5. **Nmap Scripting Engine (NSE)**

### Common Scan Types

| Option | Scan |
|---|---|
| `-sS` | TCP SYN scan |
| `-sT` | TCP Connect scan |
| `-sA` | TCP ACK scan |
| `-sU` | UDP scan |
| `-sN` | Null scan |
| `-sF` | FIN scan |
| `-sX` | Xmas scan |
| `-sI` | Idle scan |
| `-sO` | IP protocol scan |

### Useful Detection Options

- `-sV` — service/version detection
- `-O` — OS detection
- `--script` — NSE script execution

Example from the notes:

```bash
nmap -sS -sV -O --script=vuln <target>
```

Use vulnerability-oriented NSE scripts only against systems that are explicitly in scope.

---

## 🧭 Module Workflow

The overall workflow represented by these notes is:

```
Target
  ↓
Host & Port Discovery
  ↓
Service Enumeration
  ↓
Web / SMB / FTP / SNMP Enumeration
  ↓
Technology & Version Identification
  ↓
Public Exploit Research
  ↓
Vulnerability Validation
  ↓
Exploitation
  ↓
Initial Access
  ↓
Local Enumeration
  ↓
Privilege Escalation
```

---

## 🛠️ Tools Covered

**Nmap · Netcat · Gobuster · FFUF · curl · WhatWeb · Searchsploit · Metasploit Framework · LinEnum · LinPEAS · Seatbelt · JAWS · WinPEAS · GTFOBins · LOLBAS · snmpwalk · onesixtyone · smbclient**

---

## 🧠 Key Learning Points

### Enumeration Comes First

Accurate service and technology identification makes later vulnerability research more effective.

### Version Information Matters

Service banners and version detection can provide clues for researching known vulnerabilities.

### Web Enumeration Expands the Attack Surface

Directories, subdomains, source code, headers, and technology fingerprints can reveal additional paths for assessment.

### Exploit Validation Is Important

A public exploit should not be assumed to work simply because the product or version appears related. Validate that the target is actually affected before exploitation.

### Privilege Escalation Requires Enumeration

After obtaining access, carefully review permissions, services, software, credentials, scheduled tasks, and other local conditions.

---

## 📖 Recommended Study Order

For this module, study in this order:

**1. Network Enumeration with Nmap → 2. Service Scanning → 3. Web Enumeration → 4. Public Exploits & Metasploit → 5. Privilege Escalation**

This sequence builds from discovery toward post-exploitation.

---

## ⚠️ Responsible Use

These notes are intended for:

**CPTS Preparation · Hack The Box · CTFs · Security Labs · Authorized Penetration Testing**

Only scan, enumerate, exploit, or attempt privilege escalation against systems for which you have explicit permission.

Keep exploit testing inside the defined scope and use lab environments when practicing techniques that may affect service availability or system integrity.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical starting point for building the enumeration, exploitation, and privilege-escalation fundamentals required for penetration testing.**
