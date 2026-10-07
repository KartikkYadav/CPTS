# Network Enumeration with Nmap - HTB Academy Guide

A focused CPTS module covering **Nmap fundamentals, port and service discovery, Nmap Scripting Engine (NSE), vulnerability-oriented scanning, and firewall/IDS/IPS evasion labs**.

This module builds practical Nmap skills from basic scanning through service enumeration and controlled lab scenarios involving filtered traffic and alternate scanning techniques.

---

## 📚 Module Overview

The notes follow this progression:

**Nmap Fundamentals → Scan Types → Port & Service Enumeration → NSE → Vulnerability Checks → Firewall/IDS/IPS Evasion Labs**

The module is especially useful for understanding how Nmap can be used to identify hosts, ports, services, versions, operating-system information, and other exposed details during an authorized assessment.

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [Introduction to Nmap](./01.Introduction%20to%20Nmap.MD) | Nmap architecture, scan types, TCP SYN scanning, service detection, and OS detection |
| 02 | [Nmap Scripting Engine](./02.Nmap%20Scripting%20Engine.md) | NSE categories, default scripts, service enumeration, vulnerability checks, and aggressive scanning |
| 03 | [Firewall & IDS/IPS Evasion Labs](./03.Firewall%20%26%20IDS_IPS%20Evasion%20Labs.md) | Easy-to-hard labs covering filtered TCP scans, UDP discovery, full-port scans, and source-port-based testing |

---

## 🛰️ 01. Introduction to Nmap

**[Introduction to Nmap](./01.Introduction%20to%20Nmap.MD)**

Introduces **Network Mapper (Nmap)** as an open-source tool for network analysis and security auditing.

### Core Capabilities

- Host discovery
- Port scanning
- Service enumeration
- Version detection
- OS detection
- Firewall / IDS analysis
- Network mapping
- Response analysis

### Nmap Scan Architecture

```text
Host Discovery
      ↓
Port Scanning
      ↓
Service Enumeration
      ↓
OS Detection
      ↓
NSE / Further Checks
```

### Common Scan Types

| Option | Scan Type |
|---|---|
| `-sS` | TCP SYN scan |
| `-sT` | TCP Connect scan |
| `-sA` | TCP ACK scan |
| `-sW` | TCP Window scan |
| `-sM` | TCP Maimon scan |
| `-sU` | UDP scan |
| `-sN` | Null scan |
| `-sF` | FIN scan |
| `-sX` | Xmas scan |
| `-sI` | Idle scan |
| `-sY / -sZ` | SCTP scans |
| `-sO` | IP protocol scan |
| `-b` | FTP bounce scan |

### Basic Syntax

```bash
nmap <scan types> <options> <target>
```

Example:

```bash
nmap -sS 192.168.1.1
```

### Service & OS Detection

The notes highlight:

```bash
nmap -sS -sV -O --script=vuln <target>
```

where:

- `-sV` → service/version detection
- `-O` → OS detection
- `--script` → NSE scripts

---

## ⚙️ TCP SYN Scan

The module explains the TCP SYN scan (`-sS`) and how Nmap interprets responses.

| Response | Interpretation |
|---|---|
| SYN-ACK | Port is open |
| RST | Port is closed |
| No response | Port may be filtered |

The notes describe SYN scanning as fast and as a commonly used Nmap scan technique.

---

## 📜 02. Nmap Scripting Engine (NSE)

**[Nmap Scripting Engine](./02.Nmap%20Scripting%20Engine.md)**

NSE extends Nmap through **Lua scripts**, allowing additional service interaction, enumeration, and vulnerability checks.

### NSE Categories

| Category | Purpose |
|---|---|
| `auth` | Authentication-related checks |
| `broadcast` | Broadcast-based discovery |
| `brute` | Brute-force testing |
| `default` | Default scripts used by `-sC` |
| `discovery` | Service and information discovery |
| `dos` | DoS testing |
| `exploit` | Exploitation of known vulnerabilities |
| `external` | Uses external services |
| `fuzzer` | Fuzzing / malformed input |
| `intrusive` | Potentially intrusive scripts |
| `malware` | Malware detection |
| `safe` | Non-intrusive scripts |
| `version` | Version-related detection |
| `vuln` | Vulnerability detection |

### Default Scripts

```bash
nmap <target> -sC
```

### Script Category

```bash
nmap <target> --script <category>
```

Example:

```bash
nmap <target> --script vuln
```

### Specific Scripts

```bash
nmap <target> --script script1,script2
```

The notes demonstrate SMTP enumeration with:

```bash
nmap 10.129.2.28 -p 25 --script banner,smtp-commands
```

### Aggressive Scan

```bash
nmap <target> -A
```

The source notes describe `-A` as combining:

- Version detection
- OS detection
- Traceroute
- Default NSE scripts

---

## 🔍 Vulnerability-Oriented Scanning

The NSE notes include:

```bash
nmap <target> -p 80 -sV --script vuln
```

The purpose is to combine service identification with vulnerability-oriented NSE checks and map observed services or applications to known weaknesses.

NSE output can provide clues such as:

- Application endpoints
- Service information
- User-related information
- Known vulnerabilities
- CVE references

---

## 🔐 03. Firewall & IDS/IPS Evasion Labs

**[Firewall & IDS/IPS Evasion Labs](./03.Firewall%20%26%20IDS_IPS%20Evasion%20Labs.md)**

Contains three progressive lab scenarios.

### 🧪 Lab 1 — Easy

**Objective:** Identify the operating system.

Method:

```bash
nmap -sV <IP>
```

The source notes describe service/version information as the clue used to identify the OS.

---

### 🧪 Lab 2 — Medium

**Objective:** Identify the DNS server version.

The normal TCP scan is filtered, so the lab switches to UDP:

```bash
nmap -p 53 -sU <IP>
```

The lab demonstrates how different firewall rules and transport protocols can produce different scan results.

---

### 🧪 Lab 3 — Hard

**Objective:** Discover a newly added service, identify its version, and retrieve the lab flag.

The notes use:

```bash
nmap -p- -Pn -sV <IP>
```

Key flags:

| Flag | Purpose |
|---|---|
| `-p-` | Scan all 65,535 TCP ports |
| `-Pn` | Skip host discovery |
| `-sV` | Service/version detection |

The lab then identifies a service on a non-standard port and uses a source-port-based connection test.

Example from the lab notes:

```bash
sudo ncat -nv --source-port 53 <IP> 50000
```

The source material presents this as a controlled lab technique for testing firewall behavior around trusted-source-port rules.

---

## 🧠 CPTS Memory Cheatsheet

| Option | Remember |
|---|---|
| `-sS` | TCP SYN scan |
| `-sT` | TCP Connect scan |
| `-sU` | UDP scan |
| `-sV` | Service/version detection |
| `-O` | OS detection |
| `-sC` | Default NSE scripts |
| `-A` | Aggressive scan |
| `-p-` | All TCP ports |
| `-Pn` | Skip host discovery |
| `--script vuln` | Vulnerability-oriented NSE scripts |

### Core Lab Pattern

```text
Something Missing?
      ↓
Scan More Ports
      ↓
Use -Pn when Host Discovery Fails
      ↓
Check UDP where Appropriate
      ↓
Review Firewall Behavior
      ↓
Validate the Service
```

---

## 🧭 Recommended Nmap Workflow

A practical sequence from the module is:

### 1. Host Discovery

Determine whether the target responds to discovery probes.

### 2. Port Scanning

Identify exposed TCP/UDP ports.

### 3. Service Enumeration

Use service and version detection to identify what is running.

### 4. OS Identification

Use available Nmap information and service responses to develop an OS fingerprint.

### 5. NSE

Use appropriate NSE scripts for additional enumeration or vulnerability checks.

### 6. Re-evaluate Filtering

When results are incomplete, consider whether firewalls or IDS/IPS controls are affecting visibility.

### 7. Validate Findings

Confirm interesting services and versions before moving into vulnerability research or exploitation.

---

## 🛠️ Tools Covered

**Nmap · Ncat**

The module is primarily Nmap-focused, with Ncat used in the firewall/IDS/IPS lab scenarios.

---

## 🧠 Key Learning Points

### Nmap Is More Than Port Scanning

The notes cover a broader workflow that includes host discovery, service detection, OS identification, NSE, and vulnerability checks.

### Version Information Is Valuable

Knowing the service and version helps with subsequent research and validation.

### UDP Matters

A service can behave differently over UDP and TCP, so protocol selection can affect what is discovered.

### Filtering Changes Results

Firewalls and other security controls can cause ports or hosts to appear differently depending on the scan technique.

### Scan Progressively

Start with appropriate discovery and enumeration, then increase scan scope or specificity when the initial results are incomplete.

---

## 📖 Recommended Study Order

Study this module in the following order:

**1. Introduction to Nmap → 2. Scan Types → 3. Service/Version Detection → 4. NSE → 5. Vulnerability Checks → 6. Firewall/IDS/IPS Labs**

After this module, continue with the CPTS **Footprinting and Information Gathering** sections and apply the Nmap skills during service enumeration.

---

## ⚠️ Responsible Use

These techniques are intended for:

**CPTS Preparation · Hack The Box · CTFs · Security Labs · Authorized Penetration Testing**

Only scan systems and networks that you are explicitly authorized to assess.

Intrusive NSE categories, aggressive scans, and firewall/IDS/IPS evasion techniques can affect systems or trigger security controls. Use them only within the defined scope and preferably in controlled lab environments while learning.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A focused CPTS reference for Nmap-based network enumeration, NSE, service discovery, vulnerability checks, and controlled firewall/IDS/IPS testing.**
