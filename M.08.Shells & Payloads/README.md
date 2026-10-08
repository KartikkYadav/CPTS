# Shells & Payloads - HTB Academy Guide

A practical CPTS module covering **shell fundamentals, bind and reverse shells, Metasploit payload delivery, MSFvenom, Windows infiltration, interactive TTY shells, and web-shell frameworks**.

---

## 📚 Module Overview

The module progresses from understanding what a shell is to obtaining, stabilizing, and interacting with remote shells.

**Shell Fundamentals → Bind Shells → Reverse Shells → Metasploit Payloads → MSFvenom → Windows Infiltration → TTY Upgrade → Web Shells**

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [Anatomy of a Shell](./01.Anatomy%20of%20a%20Shell.md) | Terminal emulators, command interpreters, Bash, PowerShell, and shell identification |
| 02 | [Bind Shells](./02.Bind%20Shells.md) | Target-side listeners and Netcat-based shell connections |
| 03 | [Reverse Shells](./03.Reverse%20Shells.md) | Attacker listeners, outbound connections, PowerShell reverse shells |
| 04 | [Automating Payloads & Delivery with Metasploit](./04.%20Automating%20Payloads%20%26%20Delivery%20with%20Metasploit.md) | Metasploit modules, payloads, Meterpreter, and SMB exploitation |
| 05 | [Crafting Payloads with MSFvenom](./05.Crafting%20Payloads%20with%20MSFvenom.md) | Payload generation, formats, staged/stageless payloads |
| 06 | [Infiltrating Windows](./06.Infiltrating%20Windows.md) | Windows fingerprinting, common vulnerabilities, payload delivery, and shells |
| 09 | [Spawn Interactive TTY Shell](./09.Spawn%20Interactive%20TTY%20Shell.md) | Linux infiltration and upgrading non-TTY shells |
| 10 | [Spawning Interactive Shells](./10.Spawning%20Interactive%20Shells.md) | Shell upgrades using Python, Perl, Ruby, Lua, AWK, Vim, and shell binaries |
| 11 | [Introduction to Web Shells](./11.Introduction%20to%20Web%20Shells.md) | Web shells, RCE, upload vulnerabilities, and common web-shell languages |
| 12 | [Laudanum - Web Shell Framework](./12.%23%20Laudanum%20-%20Web%20Shell%20Framework.md) | Prebuilt ASP/ASPX/JSP/PHP web shells |
| 13 | [ASPX Web Shells & Antak](./13.ASPX%20Web%20Shells%20%26%20Antak.md) | ASPX shells, Nishang, Antak, and PowerShell-based interaction |
| 14 | [PHP Web Shells](./14.PHP%20Web%20Shells.md) | PHP uploads, Burp-assisted validation bypass, and browser-based command execution |

---

## 🧠 01. Anatomy of a Shell

**[Anatomy of a Shell](./01.Anatomy%20of%20a%20Shell.md)** explains the relationship between a **terminal emulator** and a **shell**.

### Core Concepts

- Terminal emulator
- Command Language Interpreter
- Bash
- PowerShell
- Zsh
- Shell prompt
- Environment variables

Identify the current shell with:

```bash
ps
env
```

The notes emphasize that the terminal emulator is the interface, while the shell interprets commands and interacts with the operating system.

---

## 🔗 02. Bind Shells

**[Bind Shells](./02.Bind%20Shells.md)** cover the model where the target listens for an incoming connection.

```text
Attacker  ───────>  Target Listener
```

### Netcat

Listener:

```bash
nc -lvnp 7777
```

Client:

```bash
nc -nv <TARGET_IP> 7777
```

The notes explain that a basic TCP connection is not automatically a shell; command execution requires shell input/output to be connected to the listener.

### Limitations

Bind shells can be affected by:

- Inbound firewall restrictions
- NAT/PAT
- Host-based firewalls
- Listener visibility

---

## 🔄 03. Reverse Shells

**[Reverse Shells](./03.Reverse%20Shells.md)** reverse the connection direction.

```text
Target  ───────>  Attacker Listener
```

The source notes explain why reverse shells are commonly used when inbound access is restricted.

### Listener Example

```bash
sudo nc -lvnp 443
```

### Windows Context

PowerShell can establish a TCP connection back to the listener and provide remote command execution.

The notes also highlight that reverse shells can be detected by AV/EDR, network monitoring, and traffic inspection.

---

## 🚀 04. Automating Payloads & Delivery with Metasploit

**[Automating Payloads & Delivery with Metasploit](./04.%20Automating%20Payloads%20%26%20Delivery%20with%20Metasploit.md)** introduces Metasploit as an exploitation and payload-delivery framework.

### Module Types

| Type | Purpose |
|---|---|
| Exploit | Exploit vulnerabilities |
| Payload | Command/shell execution |
| Auxiliary | Scanning and enumeration |
| Post | Post-exploitation |
| Encoder | Payload transformation |
| NOP | No-operation instructions |

### Typical Workflow

```text
Enumerate
   ↓
Identify Service
   ↓
Search Metasploit
   ↓
Select Module
   ↓
Configure Options
   ↓
Exploit
   ↓
Meterpreter / Shell
```

The notes use SMB and the `psexec` module as an example and introduce Meterpreter for post-exploitation interaction.

---

## 🧪 05. Crafting Payloads with MSFvenom

**[Crafting Payloads with MSFvenom](./05.Crafting%20Payloads%20with%20MSFvenom.md)** covers payload generation.

### List Payloads

```bash
msfvenom -l payloads
```

### Payload Naming

Example:

```text
linux/x64/shell_reverse_tcp
```

The name describes:

**Target OS → Architecture → Shell Type → Connection Type**

### Staged vs Stageless

| Type | Concept |
|---|---|
| Staged | Initial payload connects back and retrieves additional stages |
| Stageless | Complete payload is contained in one payload |

The notes include Linux ELF and Windows EXE generation and discuss listener setup and payload-delivery considerations.

---

## 🪟 06. Infiltrating Windows

**[Infiltrating Windows](./06.Infiltrating%20Windows.md)** focuses on Windows as a common penetration-testing target.

### Fingerprinting

Windows hosts can be identified using:

- TTL indicators
- Nmap OS detection
- Service banners
- Common Windows ports

Common ports referenced include:

**135 · 139 · 445**

### Vulnerabilities Covered

The notes reference Windows vulnerabilities such as:

- MS08-067
- EternalBlue / MS17-010
- PrintNightmare
- BlueKeep
- Zerologon
- SeriousSam
- SigRed

### Payload Types

- DLL
- BAT
- VBS
- MSI
- PowerShell

### Tools

**MSFvenom · Metasploit · Nishang · Darkarmour · Mythic C2**

The source also covers checking SMB vulnerabilities and obtaining Meterpreter access.

---

## 🐧 09. Spawn Interactive TTY Shell

**[Spawn Interactive TTY Shell](./09.Spawn%20Interactive%20TTY%20Shell.md)** combines Linux web-server infiltration with shell stabilization.

The notes demonstrate:

```bash
python -c 'import pty; pty.spawn("/bin/sh")'
```

This is useful when a compromised service provides only a limited shell.

### Why TTY Matters

A proper interactive TTY can improve:

- Terminal interaction
- Command handling
- Shell stability
- Privilege-escalation workflows
- Interactive programs

---

## 🖥️ 10. Spawning Interactive Shells

**[Spawning Interactive Shells](./10.Spawning%20Interactive%20Shells.md)** provides multiple fallback methods for upgrading or spawning shells.

### Methods Covered

Python:

```bash
python -c 'import pty; pty.spawn("/bin/sh")'
```

Shell:

```bash
/bin/sh -i
```

Perl:

```bash
perl -e 'exec "/bin/sh";'
```

Ruby:

```bash
ruby -e 'exec "/bin/sh"'
```

Lua:

```bash
lua -e 'os.execute("/bin/sh")'
```

AWK:

```bash
awk 'BEGIN {system("/bin/sh")}'
```

Find:

```bash
find . -exec /bin/sh \; -quit
```

Vim:

```bash
vim -c ':!/bin/sh'
```

The notes also connect shell stabilization with privilege enumeration such as:

```bash
sudo -l
```

---

## 🌐 11. Introduction to Web Shells

**[Introduction to Web Shells](./11.Introduction%20to%20Web%20Shells.md)** introduces browser-accessible command execution.

### Common Entry Points

Web shells may result from:

- File-upload vulnerabilities
- SQL injection
- Command injection
- LFI/RFI
- Weak upload validation
- Misconfigured services
- Vulnerable application features

### Common Web-Shell Languages

| Technology | Extension |
|---|---|
| PHP | `.php` |
| JSP | `.jsp` |
| ASP.NET | `.aspx` |
| Perl | `.pl` |
| Python | `.py` |

The notes explain that web shells are often used as an initial foothold before obtaining a more stable shell.

---

## 🧰 12. Laudanum - Web Shell Framework

**[Laudanum - Web Shell Framework](./12.%23%20Laudanum%20-%20Web%20Shell%20Framework.md)** covers Laudanum as a collection of prebuilt web shells and payloads.

### Supported Technologies

- ASP
- ASPX
- JSP
- PHP
- ColdFusion

Typical Kali location:

```bash
/usr/share/laudanum
```

The notes discuss modifying shells before deployment, including callback configuration and operational considerations.

---

## 🪟 13. ASPX Web Shells & Antak

**[ASPX Web Shells & Antak](./13.ASPX%20Web%20Shells%20%26%20Antak.md)** focuses on ASP.NET web shells for Windows/IIS environments.

### Antak

Antak is introduced as a **PowerShell-based ASPX web shell** from the Nishang framework.

The notes cover:

- Finding Antak
- Copying and modifying the shell
- Authentication
- PowerShell command execution
- File upload/download
- Encoding and execution
- `web.config` parsing
- SQL queries

---

## 🐘 14. PHP Web Shells

**[PHP Web Shells](./14.PHP%20Web%20Shells.md)** covers PHP web shells on Linux web servers.

The notes demonstrate an upload-validation scenario where Burp Suite is used to modify the request's content type.

Conceptually:

```text
Upload Restriction
      ↓
Intercept Request
      ↓
Modify Request
      ↓
Application Validation
      ↓
PHP File Accepted
      ↓
Browser-Based Command Execution
```

The note also emphasizes that web shells are commonly non-interactive and that reverse shells are generally more usable after initial access.

---

## 🧭 Shell Workflow

A practical workflow across the module is:

```text
Identify Target
      ↓
Enumerate Services / Web App
      ↓
Find Initial Execution Path
      ↓
Choose Bind / Reverse / Web Shell
      ↓
Establish Access
      ↓
Stabilize Shell
      ↓
Enumerate Host
      ↓
Privilege Escalation
      ↓
Continue Authorized Assessment
```

---

## 🧠 Key Learning Points

### Bind vs Reverse Shell

**Bind:** target listens.

**Reverse:** target connects back.

### Shell vs TTY

A command shell may work but still be uncomfortable or unsuitable for interactive tools. TTY upgrades improve terminal behavior.

### Payload Type Matters

MSFvenom supports multiple target platforms, architectures, formats, and staged/stageless approaches.

### Web Shells Are Usually Initial Access

A browser-based shell can provide execution but may be unstable, logged, restricted, or detected.

### Security Controls Matter

AV, EDR, firewalls, WAFs, and network monitoring can detect or block shell and payload activity.

---

## 🛠️ Tools Covered

**Netcat · Nmap · Metasploit · MSFvenom · Meterpreter · Nishang · Laudanum · Burp Suite · Python · PowerShell · rdesktop · xfreerdp**

---

## 📖 Recommended Study Order

**1. Anatomy of a Shell → 2. Bind Shells → 3. Reverse Shells → 4. Metasploit Payload Delivery → 5. MSFvenom → 6. Windows Infiltration → 7. TTY Shells → 8. Web Shells → 9. Laudanum / Antak / PHP Shells**

---

## ⚠️ Responsible Use

Use these techniques only for:

**HTB Academy · CPTS Preparation · CTFs · Security Labs · Authorized Penetration Testing**

Payloads, reverse shells, web shells, and exploitation techniques can provide remote command execution. Practice them only on systems you are explicitly authorized to test.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for shells, payload generation, Metasploit, TTY stabilization, Windows infiltration, and web-shell techniques.**
