# HTB CPTS: Certified Penetration Testing Specialist

![Platform](https://img.shields.io/badge/Platform-Hack%20The%20Box-9FEF00?style=flat-square&logo=hackthebox&logoColor=white)
![Path](https://img.shields.io/badge/Path-CPTS-blue?style=flat-square)
![Modules](https://img.shields.io/badge/Modules-28-informational?style=flat-square)
![Completed](https://img.shields.io/badge/Completed-15%2F28-success?style=flat-square)
![Status](https://img.shields.io/badge/Status-In%20Progress-orange?style=flat-square)

Personal notes, cheat sheets, commands, and walkthroughs from my journey through the Hack The Box **Certified Penetration Testing Specialist (CPTS)** job-role path. The path covers the full penetration testing lifecycle: enumeration, exploitation, post-exploitation, lateral movement, and professional reporting.

> **Disclaimer:** This repository is for educational purposes only. All techniques were practiced in authorized lab environments provided by Hack The Box. Do not use any of this material against systems you do not own or have explicit written permission to test.

---

## Table of Contents

- [Overview](#overview)
- [Progress Summary](#progress-summary)
- [Module Index](#module-index)
  - [1. Foundations](#1-foundations)
  - [2. Reconnaissance and Enumeration](#2-reconnaissance-and-enumeration)
  - [3. Exploitation and Post-Exploitation](#3-exploitation-and-post-exploitation)
  - [4. Web Application Attacks](#4-web-application-attacks)
  - [5. Active Directory and Network Attacks](#5-active-directory-and-network-attacks)
  - [6. Privilege Escalation](#6-privilege-escalation)
  - [7. Reporting and Capstone](#7-reporting-and-capstone)
- [Tools Covered](#tools-covered)
- [Repository Structure](#repository-structure)
- [Legend](#legend)
- [Disclaimer](#disclaimer)

---

## Overview

| Item | Detail |
|------|--------|
| Path | Certified Penetration Testing Specialist (CPTS) |
| Provider | Hack The Box Academy |
| Total Modules | 28 |
| Focus | Offensive security, web exploitation, Active Directory, reporting |
| Goal | Pass the CPTS certification exam |


---

## Module Index

### 1. Foundations

| # | Module | Category | Difficulty | Duration | Tier | Status |
|---|--------|----------|------------|----------|------|--------|
| 1 | Penetration Testing Process | General | Fundamental | 6h | Tier I | Completed |
| 2 | Getting Started | Offensive | Fundamental | 1d | Tier 0 | Completed |
| 3 | File Transfers | Offensive | Medium | 3h | Tier 0 | Completed |

### 2. Reconnaissance and Enumeration

| # | Module | Category | Difficulty | Duration | Tier | Status |
|---|--------|----------|------------|----------|------|--------|
| 4 | Network Enumeration with Nmap | Offensive | Easy | 7h | Tier I | Completed |
| 5 | Footprinting | Offensive | Medium | 2d | Tier II | Completed |
| 6 | Information Gathering - Web Edition | Offensive | Easy | 1d | Tier II | Completed |
| 7 | Vulnerability Assessment | Offensive | Easy | 2h | Tier 0 | Completed |

### 3. Exploitation and Post-Exploitation

| # | Module | Category | Difficulty | Duration | Tier | Status |
|---|--------|----------|------------|----------|------|--------|
| 8 | Shells & Payloads | Offensive | Medium | 2d | Tier I | Completed |
| 9 | Using the Metasploit Framework | Offensive | Easy | 5h | Tier 0 | Completed |
| 10 | Password Attacks | Offensive | Medium | 3d | Tier I | In Progress (65.38%) |
| 11 | Attacking Common Services | Offensive | Medium | 1d | Tier II | In Progress (63.16%) |
| 12 | Pivoting, Tunneling, and Port Forwarding | Offensive | Medium | 2d | Tier II | Not Started |

### 4. Web Application Attacks

| # | Module | Category | Difficulty | Duration | Tier | Status |
|---|--------|----------|------------|----------|------|--------|
| 13 | Using Web Proxies | Offensive | Easy | 1d | Tier II | Completed |
| 14 | Attacking Web Applications with Ffuf | Offensive | Easy | 5h | Tier 0 | Completed |
| 15 | Login Brute Forcing | Offensive | Easy | 6h | Tier II | Completed |
| 16 | SQL Injection Fundamentals | Offensive | Medium | 1d | Tier 0 | Completed |
| 17 | SQLMap Essentials | Offensive | Easy | 4h | Tier II | In Progress (45.45%) |
| 18 | Cross-Site Scripting (XSS) | Offensive | Easy | 6h | Tier II | Completed |
| 19 | File Inclusion | Offensive | Medium | 1d | Tier 0 | Completed |
| 20 | File Upload Attacks | Offensive | Medium | 1d | Tier II | In Progress (45.45%) |
| 21 | Command Injections | Offensive | Medium | 6h | Tier II | Not Started |
| 22 | Web Attacks | Offensive | Medium | 2d | Tier II | Not Started |
| 23 | Attacking Common Applications | Offensive | Medium | 4d | Tier II | Not Started |

### 5. Active Directory and Network Attacks

| # | Module | Category | Difficulty | Duration | Tier | Status |
|---|--------|----------|------------|----------|------|--------|
| 24 | Active Directory Enumeration & Attacks | Offensive | Medium | 7d | Tier II | In Progress (8.33%) |

### 6. Privilege Escalation

| # | Module | Category | Difficulty | Duration | Tier | Status |
|---|--------|----------|------------|----------|------|--------|
| 25 | Linux Privilege Escalation | Offensive | Easy | 1d | Tier II | In Progress (14.29%) |
| 26 | Windows Privilege Escalation | Offensive | Medium | 4d | Tier II | In Progress (15.15%) |

### 7. Reporting and Capstone

| # | Module | Category | Difficulty | Duration | Tier | Status |
|---|--------|----------|------------|----------|------|--------|
| 27 | Documentation & Reporting | General | Easy | 2d | Tier II | Not Started |
| 28 | Attacking Enterprise Networks | Offensive | Medium | 2d | Tier II | Not Started |

---

## Tools Covered

| Area | Tools |
|------|-------|
| Enumeration | Nmap, Nikto, Gobuster, Ffuf |
| Web Testing | Burp Suite, OWASP ZAP, SQLMap |
| Exploitation | Metasploit Framework, custom payloads and shells |
| Password Attacks | Hydra, Hashcat, John the Ripper |
| Network Analysis | Wireshark, tcpdump |
| Pivoting | SSH tunneling, Chisel, Ligolo-ng, Proxychains |
| Active Directory | BloodHound, Impacket, CrackMapExec, Rubeus, Mimikatz |
| Privilege Escalation | LinPEAS, WinPEAS, GTFOBins, LOLBAS |

*Tool list reflects the general path curriculum and my own workflow.*

---

## Repository Structure

```
CPTS/
├── README.md
├── 01-Penetration-Testing-Process/
├── 02-Getting-Started/
├── 03-Network-Enumeration-with-Nmap/
├── 04-Footprinting/
├── ...
├── 28-Attacking-Enterprise-Networks/
├── cheatsheets/
├── scripts/
└── reports/
```

Each module folder contains:

- `notes.md`: key concepts and takeaways
- `commands.md`: commands and syntax worth remembering
- `labs/`: lab and skills assessment write-ups (flags and credentials redacted)

---

## Legend

| Term | Meaning |
|------|---------|
| Tier 0 to Tier II | HTB Academy module tier (Tier 0 is the most accessible) |
| Fundamental / Easy / Medium | Module difficulty rating |
| h / d | Estimated hours / days to complete |
| Completed | Module and all sections finished |
| In Progress | Percentage shown is current completion |
| Not Started | Module not yet begun |

---

## Disclaimer

This content is shared for learning and reference. Hack The Box Academy content is the property of Hack The Box; this repository contains only my own notes and paraphrased summaries. No flags, credentials, or exam material are published here.

---

*Last updated: October 2026*
