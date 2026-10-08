# VulnHub Machines for CPTS Preparation - HTB Academy Guide

A supplementary collection of **VulnHub practice machines and preparation notes** intended to reinforce the penetration-testing methodology covered throughout the CPTS learning path.

This section is separate from the HTB Academy modules and is used as additional hands-on practice.

---

## 📂 Contents

| # | Resource | Purpose |
|---|---|---|
| 01 | [Easy to Intermediate Machines](./01.Easy%20to%20Intermediate_Machine.md) | Curated list of VulnHub machines suitable for CPTS-style practice |
| 02 | [Machine Notes](./02.Machine_Notes.md) | Additional machine notes and practice space |

---

## 🧪 01. Easy to Intermediate Machines

**[Top 10 VulnHub Machines for CPTS](./01.Easy%20to%20Intermediate_Machine.md)** contains a curated list of ten machines recommended in the source notes:

1. Basic Pentesting: 1
2. Kioptrix #1
3. Kioptrix #2
4. DC: 1
5. DC: 2
6. LazySysAdmin
7. FristiLeaks
8. Stapler
9. VulnOS: 2
10. DC: 5

These machines can be used to practice the core penetration-testing cycle in a standalone lab environment.

---

## 🧭 Suggested Practice Methodology

Use the VulnHub machines to reinforce the methodology from the CPTS modules:

```text
Scope / Lab Setup
      ↓
Reconnaissance
      ↓
Network Enumeration
      ↓
Service Enumeration
      ↓
Web / Service Assessment
      ↓
Vulnerability Research
      ↓
Initial Access
      ↓
Privilege Escalation
      ↓
Post-Exploitation
      ↓
Documentation
```

### Recommended Approach

For each machine, record:

- Target IP
- Open ports
- Services and versions
- Web technologies
- Interesting files/directories
- Credentials discovered in the lab
- Vulnerabilities identified
- Initial-access path
- Privilege-escalation path
- Evidence and screenshots
- Final attack chain

---

## 🔗 Related CPTS Modules

These practice machines complement the skills developed in:

**Getting Started · File Transfer · Network Enumeration with Nmap · Footprinting · Information Gathering · Vulnerability Assessment · Attacking Common Services · Shells & Payloads · Password Attacks · Web Proxies · FFUF · Login Brute Forcing · SQL Injection · SQLMap · XSS · File Inclusion · File Uploads · Active Directory · Linux Privilege Escalation · Windows Privilege Escalation**

---

## 🧠 What to Practice

The strongest value of these machines comes from chaining individual techniques rather than solving each service in isolation.

Focus on:

**Enumeration → Correlation → Validation → Exploitation → Privilege Escalation → Reporting**

Pay attention to how a low-impact discovery can become the entry point for a larger attack path.

---

## ⚠️ Responsible Use

VulnHub machines are designed for intentionally vulnerable lab environments.

Run them only in isolated or authorized environments and do not expose intentionally vulnerable machines to untrusted networks.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A supplementary VulnHub practice section for strengthening CPTS penetration-testing skills through hands-on vulnerable machines.**
