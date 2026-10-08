# CPTS - HTB Academy Guide

A structured collection of my **Hack The Box Academy CPTS preparation notes, commands, lab walkthroughs, and practical security references**.

The repository follows a penetration-testing learning path from **reconnaissance and enumeration through exploitation, web application security, Active Directory, and privilege escalation**, with additional VulnHub practice machines.

---

## 🎯 Repository Focus

**Reconnaissance → Enumeration → Vulnerability Assessment → Exploitation → Shells & Payloads → Web Security → Active Directory → Privilege Escalation**

The notes are organized as practical study material for:

- CPTS preparation
- Hack The Box Academy labs
- CTF practice
- Security-lab exercises
- Authorized penetration testing

---

## 📚 Module Index

| Module | Focus |
|---|---|
| [Getting Started](./M.01.Getting_Started) | Service scanning, web enumeration, public exploits, Metasploit, privilege escalation, and Nmap |
| [File Transfer](./M.02.File_Transfer) | Linux/Windows file-transfer methods, protocols, PowerShell, WebDAV, and integrity checks |
| [Network Enumeration with Nmap](./M.03.Network%20Enumeration%20with%20Nmap) | Nmap scan techniques, NSE, service discovery, and firewall/IDS/IPS labs |
| [Footprinting](./M.04.Footprinting) | FTP, SMB, NFS, DNS, mail services, SNMP, databases, IPMI, and service-focused labs |
| [Information Gathering - Web Edition](./M.05.Information%20Gathering%20-%20Web%20Edition) | WHOIS, DNS, subdomains, AXFR, VHosts, fingerprinting, crawling, and recon automation |
| [Vulnerability Assessment](./M.06.Vulnerability_Assismen) | Nessus, OpenVAS/GVM, credentialed scanning, scan policies, and assessment labs |
| [Attacking Common Services](./M.07.Attacking%20common%20dervices) | FTP, MSSQL, RDP, DNS, subdomain takeover, and common service attack paths |
| [Shells & Payloads](./M.08.Shells%20%26%20Payloads) | Bind/reverse shells, Metasploit, MSFvenom, Windows infiltration, TTY shells, and web shells |
| [Active Directory](./M.09.Active_Directory) | PowerView, BloodHound, SharpHound, Kerberos, Impacket, RPC/SMB, and AD tooling |
| [Password Attacks](./M.10.Password%20Attacks) | Password cracking, John, Hashcat, wordlists, spraying, credential hunting, and password managers |
| [Using Web Proxies](./M.13.Using%20Web%20Proxies) | HTTP interception, encoding/decoding, Proxychains, Metasploit proxying, and Burp Scanner |
| [Web Application Fuzzing with FFUF](./M14.Attacking%20Web%20Applications%20with%20Ffuf%20Attacking%20Web%20Applications%20with%20Ffuf.) | Directory, page, recursive, subdomain, VHost, parameter, POST, and value fuzzing |
| [Login Brute Forcing](./M16.Login_Brute_Forcing) | Brute force, dictionary attacks, Hydra, Medusa, Basic Auth, login forms, and custom wordlists |
| [SQL Injection Fundamentals](./M17.SQL%20Injection%20Fundamentals) | SQLi fundamentals, UNION SQLi, database enumeration, file access, mitigation, and labs |
| [SQLMap](./M18.Sql_Map) | SQLMap detection, injection techniques, output interpretation, request handling, and API requests |
| [Cross-Site Scripting (XSS)](./M19.Cross_Site_Scripting_(XSS)) | Stored, Reflected, DOM-based XSS, discovery, impact, session concepts, and prevention |
| [File Inclusion](./M20.File%20Inclusion) | LFI, RFI, traversal, PHP filters, wrappers, log poisoning, uploads, and RCE paths |
| [File Uploads](./M21.File%20Uploads) | Upload validation, content/MIME checks, filter bypass testing, and secure upload architecture |
| [Windows Privilege Escalation](./M25.Windows_Priviledge_Escalation) | Windows enumeration, privileges, processes, named pipes, security controls, and escalation paths |
| [Linux Privilege Escalation](./M26.Linux%20Privilege%20Escalation) | Linux enumeration, sudo, credentials, cron, services, binaries, filesystems, and escalation paths |

---

## 🧪 Additional Practice

### VulnHub Machines

**[CPTS VulnHub Practice](./CPTS_Vulnhub_Machines_To_Do)**

Supplementary vulnerable-machine practice covering:

- Basic Pentesting: 1
- Kioptrix #1
- Kioptrix #2
- DC: 1
- DC: 2
- LazySysAdmin
- FristiLeaks
- Stapler
- VulnOS: 2
- DC: 5

These machines are useful for applying the same reconnaissance, enumeration, exploitation, and privilege-escalation concepts outside the Academy environment.

---

## 🧭 Recommended Learning Path

### 01 — Reconnaissance

Start with:

**Footprinting → Information Gathering - Web Edition**

Learn to identify:

- Domains
- Subdomains
- IPs
- DNS records
- VHosts
- Technologies
- Publicly exposed information

### 02 — Enumeration

Continue with:

**Getting Started → Network Enumeration with Nmap → Footprinting**

Focus on:

- Ports
- Services
- Versions
- Shares
- Network services
- Service-specific enumeration

### 03 — Vulnerability Assessment

Use:

**Vulnerability Assessment**

to understand:

- Scanner configuration
- Nessus
- OpenVAS
- Credentialed scanning
- Finding validation

### 04 — Exploitation & Access

Continue with:

**Attacking Common Services → Shells & Payloads → Password Attacks**

Learn how service weaknesses, credentials, and payloads can lead to initial access.

### 05 — Web Application Security

Study:

**Using Web Proxies → FFUF → Login Brute Forcing → SQL Injection → SQLMap → XSS → File Inclusion → File Uploads**

### 06 — Enterprise / Windows Security

Finish with:

**Active Directory → Windows Privilege Escalation → Linux Privilege Escalation**

---

## 🛠️ Tools Covered

| Area | Tools |
|---|---|
| Recon & Enumeration | Nmap, dig, dnsenum, Gobuster, FFUF, WhatWeb |
| Vulnerability Assessment | Nessus, OpenVAS, GVM |
| Web Security | Burp Suite, OWASP ZAP, SQLMap |
| Exploitation | Metasploit, MSFvenom |
| Password Attacks | Hydra, Medusa, John the Ripper, Hashcat |
| Network Analysis | Wireshark, tcpdump |
| Active Directory | BloodHound, SharpHound, PowerView, Rubeus, Impacket |
| Privilege Escalation | WinPEAS, LinPEAS, GTFOBins, Sysinternals |

---

## 🧠 Core Penetration-Testing Workflow

```text
Scope
  ↓
Reconnaissance
  ↓
Enumeration
  ↓
Service / Technology Identification
  ↓
Vulnerability Assessment
  ↓
Validation
  ↓
Exploitation
  ↓
Initial Access
  ↓
Privilege Escalation
  ↓
Post-Exploitation
  ↓
Documentation
```

The individual module READMEs expand each phase with commands, concepts, lab notes, and practical workflows.

---

## 📖 Study Strategy

For each module:

**Understand the concept → Practice the commands → Complete the lab → Record the attack path → Review the defensive lesson**

Focus on understanding **why a technique works**, not only memorizing commands.

---

## 📝 Repository Notes

Some source filenames retain their original HTB/CPTS naming, including numbering and spelling variations. The module READMEs provide a cleaner navigation layer while preserving links to the original note files.

The repository also contains:

- `api_report.md` — supplementary API-related material
- `CPTS_Vulnhub_Machines_To_Do/` — additional vulnerable-machine practice

---

## ⚠️ Responsible Use

This repository is intended for:

**Hack The Box Academy · CPTS Preparation · CTFs · Security Labs · Authorized Penetration Testing**

Do not apply these techniques to systems, services, accounts, or networks without explicit authorization.

Use extra care with:

- Credential attacks
- Vulnerability scanning
- Exploit execution
- Reverse shells
- Web shells
- Network poisoning
- Privilege escalation

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical CPTS study repository covering reconnaissance, enumeration, exploitation, web security, Active Directory, and privilege escalation.**
