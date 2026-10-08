# Windows Privilege Escalation - HTB Academy Guide

A practical CPTS module covering **Windows privilege-escalation fundamentals, manual host enumeration, automated tools, network information, processes, named pipes, access tokens, Windows privileges, security controls, and common escalation paths**.

---

## 📚 Module Overview

The module follows the post-compromise Windows workflow:

**Initial Access → Host Enumeration → Network Enumeration → Security Controls → Processes & Services → Privileges → Permissions → Identify Escalation Path**

A recurring theme throughout the notes is that automated tools are useful, but **manual enumeration is essential** when security controls or restricted environments prevent tool usage.

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [Introduction to Windows Privilege Escalation](./01.Introduction%20to%20Windows%20Privilege%20Escalation.md) | Privilege escalation goals, common vectors, and manual-enumeration mindset |
| 02 | [Privilege Escalation Tools](./02.Privilege%20Escalation%20Tool.md) | WinPEAS, Seatbelt, PowerUp, SharpUp, JAWS, Watson, WES-NG, LaZagne, Sysinternals |
| 03 | [Network Information Enumeration](./03.Network%20Information%20Enumeration.md) | Interfaces, ARP, routes, security controls, Defender, and AppLocker |
| 04 | [Windows Privilege Escalation Enumeration](./04.%20Windows%20Privilege%20Escalation%20Enumeration.md) | OS, patches, processes, services, software, users, and network connections |
| 05 | [Communication with Processes](./05.Communication%20with%20Processes.md) | Access tokens, sockets, localhost services, named pipes, PipeList, and AccessChk |
| 06 | [Windows Privileges](./06.Windows%20Privileges.md) | User privileges, groups, access tokens, and important Windows privilege assignments |

---

## 🎯 01. Introduction to Windows Privilege Escalation

The introductory note defines privilege escalation as obtaining higher permissions after an initial foothold.

### Typical Escalation Targets

- Local Administrator
- `NT AUTHORITY\SYSTEM`
- Another privileged local account
- A domain user with local administrator rights
- Domain-level privileged accounts where the assessment scope allows

### Common Vectors

The source notes discuss:

- User and group privileges
- UAC-related weaknesses
- Weak service permissions
- Weak file permissions
- Unpatched operating systems
- Credential theft
- Network traffic
- Scheduled tasks
- Registry misconfigurations
- DLL hijacking
- Token impersonation

### Manual Enumeration

The notes strongly emphasize manual enumeration because real environments may restrict:

- Internet access
- USB devices
- PowerShell
- Tool execution
- Uploading external binaries

Core Windows utilities such as **CMD, PowerShell, and built-in system commands** therefore remain important.

---

## 🛠️ 02. Privilege Escalation Tools

The tool reference covers:

| Tool | Purpose |
|---|---|
| Seatbelt | Local Windows security and configuration enumeration |
| WinPEAS | Broad privilege-escalation enumeration |
| PowerUp | PowerShell-based privilege-escalation checks |
| SharpUp | C# equivalent of many PowerUp checks |
| JAWS | PowerShell enumeration |
| SessionGopher | Saved-session and credential discovery |
| Watson | Missing-patch and exploit identification |
| LaZagne | Stored credential recovery |
| WES-NG | Windows exploit/patch analysis |
| Sysinternals | Microsoft administration and security utilities |
| AccessChk | Permission analysis |
| PipeList | Named-pipe enumeration |

### Tool Limitations

Automated tools can:

- Generate large amounts of output
- Produce false positives
- Miss conditions
- Trigger Defender or EDR

The recommended mindset is:

**Use Automation → Understand the Output → Manually Verify → Validate the Escalation Path**

---

## 🌐 03. Network Information Enumeration

Network enumeration can reveal additional attack surfaces and hidden network segments.

### Important Commands

```cmd
ipconfig /all
arp -a
route print
```

### Information to Collect

- Hostname
- IPv4 / IPv6 addresses
- MAC addresses
- DNS servers
- Default gateway
- Network interfaces
- Routing information
- Recently contacted hosts

### Dual-Homed Systems

A system connected to multiple networks may provide visibility into a network segment that was not previously reachable.

### Security Controls

The notes also cover checking:

```powershell
Get-MpComputerStatus
```

and:

```powershell
Get-AppLockerPolicy -Effective | Select -ExpandProperty RuleCollections
```

These checks help determine which defensive controls may affect subsequent enumeration.

---

## 🔎 04. Windows Privilege Escalation Enumeration

**[Windows Privilege Escalation Enumeration](./04.%20Windows%20Privilege%20Escalation%20Enumeration.md)** provides a structured host-enumeration checklist.

### System Information

```cmd
systeminfo
```

Review:

- OS version
- Build
- Architecture
- Installed hotfixes
- Domain/workgroup
- Network configuration

### Processes & Services

```cmd
tasklist /svc
```

Look for:

- Privileged services
- Unusual applications
- Security software
- Interesting process-to-service relationships

### Installed Software

The notes cover using WMI/PowerShell to identify installed applications and versions.

### Network Connections

```cmd
netstat -ano
```

Correlate:

**Port → PID → Process → Service**

### Users

```cmd
query user
```

This helps identify active sessions and other users on the system.

---

## 🔗 05. Communication with Processes

**[Communication with Processes](./05.Communication%20with%20Processes.md)** introduces Windows process communication and security contexts.

### Access Tokens

A Windows access token contains information including:

- User identity
- Group memberships
- Privileges
- Restrictions
- Integrity level

A process therefore executes according to the security context represented by its token.

### Localhost Services

The notes emphasize checking services bound to:

```text
127.0.0.1
::1
```

A service that is not externally reachable may still be exploitable locally if its authentication or configuration is weak.

### Named Pipes

Named pipes provide inter-process communication.

The notes cover:

```cmd
pipelist.exe /accepteula
```

and:

```powershell
gci \\.\pipe\
```

### Permission Analysis

**AccessChk** can be used to examine permissions associated with named pipes and other Windows objects.

---

## 🔐 06. Windows Privileges

**[Windows Privileges](./06.Windows%20Privileges.md)** explains special rights stored in Windows access tokens.

### Enumerate Current Privileges

```powershell
whoami /priv
```

### Enumerate Groups

```powershell
whoami /groups
```

### Complete Security Context

```powershell
whoami /all
```

### Important Privileges

| Privilege | Why It Matters |
|---|---|
| SeBackupPrivilege | May allow backup-related access to protected files |
| SeRestorePrivilege | May allow restoration/overwrite operations |
| SeTakeOwnershipPrivilege | Can allow taking ownership of securable objects |
| SeDebugPrivilege | Important for high-privilege process interaction |
| SeImpersonatePrivilege | Important privilege to investigate for impersonation paths |
| SeLoadDriverPrivilege | Allows loading/unloading drivers |

### Important Principle

A privilege marked **Disabled** should not automatically be ignored. The notes emphasize understanding whether the privilege is assigned and how it behaves in the current security context.

---

## 🧭 Windows Privilege Escalation Workflow

```text
Initial Shell
     ↓
whoami / whoami /all
     ↓
OS & Patch Enumeration
     ↓
Users & Groups
     ↓
Processes & Services
     ↓
Network Interfaces / Routes
     ↓
Security Controls
     ↓
File / Service / Registry Permissions
     ↓
Interesting Privileges
     ↓
Validate Escalation Path
     ↓
Administrator / SYSTEM
```

---

## 🧠 High-Value Enumeration Checklist

| Area | Check |
|---|---|
| Identity | `whoami`, `whoami /all` |
| Privileges | `whoami /priv` |
| Groups | `whoami /groups` |
| OS | `systeminfo` |
| Processes | `tasklist /svc` |
| Network | `ipconfig /all`, `route print`, `arp -a` |
| Connections | `netstat -ano` |
| Users | `query user` |
| Security | Defender / AppLocker |
| Named Pipes | PipeList / `gci \\.\pipe\` |
| Patches | `wmic qfe` / `Get-HotFix` |
| Software | WMI / PowerShell enumeration |

---

## 🧠 Key Learning Points

### Enumeration Comes Before Exploitation

The module repeatedly emphasizes understanding the host before selecting an escalation technique.

### Automated Tools Are Not the Whole Process

Tools such as WinPEAS and Seatbelt can speed up discovery, but manual verification remains essential.

### Privileges and Groups Are Different

A user can have an interesting assigned privilege even without being a member of the local Administrators group.

### Localhost Services Matter

Services restricted to localhost may still expose useful functionality after gaining access to the machine.

### Security Controls Influence Your Workflow

Defender, EDR, and AppLocker can affect which tools and techniques work, making defensive-control enumeration part of the assessment.

---

## 🛠️ Tools Covered

**WinPEAS · Seatbelt · PowerUp · SharpUp · JAWS · Watson · WES-NG · LaZagne · Sysinternals · AccessChk · PipeList · CMD · PowerShell**

---

## 📖 Recommended Study Order

**1. Privilege Escalation Fundamentals → 2. Automated Tools → 3. Network Enumeration → 4. Host Enumeration → 5. Process Communication → 6. Windows Privileges**

---

## ⚠️ Responsible Use

Use these techniques only for:

**HTB Academy · CPTS Preparation · CTFs · Security Labs · Authorized Windows Assessments**

Privilege escalation, credential access, process inspection, and security-control testing can affect real systems. Keep all activity within the defined scope.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Windows Security | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for Windows privilege escalation enumeration, security contexts, privileges, processes, and escalation-path analysis.**
