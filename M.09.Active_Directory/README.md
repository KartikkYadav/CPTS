# Active Directory - HTB Academy Guide

A focused CPTS reference for **Active Directory enumeration and attack-path tooling**.

This module currently contains a consolidated tool reference covering common utilities used to enumerate and interact with Windows Active Directory environments.

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [Active Directory Tools](./01.AD_Tools.md) | AD enumeration, relationship mapping, Kerberos abuse, RPC/SMB tooling, credential discovery, and network spoofing |

---

## 🛠️ Active Directory Toolset

The source note covers the following major tools and their roles:

| Tool | Primary Use |
|---|---|
| PowerView / SharpView | Active Directory situational awareness and targeted enumeration |
| BloodHound | Visualize AD relationships and identify attack paths |
| SharpHound | Collect AD objects, ACLs, GPOs, sessions, and related data for BloodHound |
| BloodHound.py | Python-based BloodHound collection from a non-domain-joined host |
| Kerbrute | AD account enumeration, password spraying, and brute-force workflows |
| Impacket | Network-protocol interaction and AD enumeration/attack utilities |
| Responder | LLMNR, NBT-NS, and MDNS poisoning |
| Inveigh | Windows/PowerShell network spoofing and poisoning |
| rpcinfo | RPC service discovery |
| rpcclient | RPC-based Windows/AD enumeration |
| CrackMapExec | SMB/WMI/WinRM/MSSQL enumeration and post-exploitation workflows |
| Rubeus | Kerberos abuse and ticket-related operations |
| GetUserSPNs.py | Discover service principal names tied to users |
| Hashcat | Password and hash recovery |
| enum4linux / enum4linux-ng | Windows and Samba enumeration |

---

## 🔎 AD Enumeration Concepts

The module connects individual tools to broader Active Directory tasks such as:

**Users → Groups → Computers → ACLs → Sessions → GPOs → Services → Kerberos Relationships**

### PowerView / SharpView

Used for gaining situational awareness, targeted enumeration, and finding potentially useful AD relationships.

### BloodHound

Provides a graphical representation of relationships between users, computers, groups, sessions, ACLs, and other AD objects.

### SharpHound

Acts as the data collector that gathers information later analyzed in BloodHound.

### BloodHound.py

Provides a Python-based collection option from systems that are not domain joined.

---

## 🔐 Kerberos-Focused Tools

The note references:

**Kerbrute · Rubeus · GetUserSPNs.py**

These tools are relevant to:

- Account enumeration
- Kerberos service discovery
- Service Principal Name enumeration
- Kerberos abuse workflows

---

## 🌐 Windows Network Enumeration

The module also references:

**rpcinfo · rpcclient · CrackMapExec · enum4linux · enum4linux-ng**

These tools can help collect information about:

- RPC services
- Domains
- Users
- Shares
- Windows/Samba hosts
- SMB-related attack surface

---

## 🕸️ Network Poisoning

The tool reference includes:

**Responder · Inveigh**

These are associated with name-resolution and network-poisoning techniques such as LLMNR, NBT-NS, and MDNS-related attacks.

These techniques can expose authentication material and therefore require careful scope control.

---

## 🧭 Practical AD Workflow

A useful learning workflow based on the tool set is:

```text
Identify Domain
      ↓
Enumerate Users / Groups / Hosts
      ↓
Enumerate SMB / RPC
      ↓
Collect AD Relationships
      ↓
Analyze BloodHound Graph
      ↓
Identify Privilege / Trust Paths
      ↓
Validate Authorized Attack Path
```

---

## 🧠 Key Learning Points

### Tool Output Must Be Correlated

Individual enumeration tools provide partial information. The useful result often comes from combining their outputs.

### BloodHound Is an Analysis Layer

SharpHound or BloodHound.py collects data; BloodHound helps turn that data into relationship and attack-path analysis.

### RPC and SMB Are Core AD Sources

Windows infrastructure exposes valuable information through RPC and SMB, making these protocols important during AD enumeration.

### Kerberos Knowledge Matters

Kerbrute, Rubeus, and GetUserSPNs.py support different parts of Kerberos-focused assessment.

---

## 📖 Recommended Study Order

**AD Fundamentals → RPC/SMB Enumeration → PowerView/SharpView → BloodHound/SharpHound → Kerbrute → Impacket → Kerberos Tooling → Network Poisoning**

---

## ⚠️ Responsible Use

Use these tools only for:

**HTB Academy · CPTS Preparation · CTFs · Security Labs · Authorized Active Directory Assessments**

Network poisoning, credential collection, password spraying, and Kerberos abuse can affect users and systems. Perform them only within a clearly authorized scope.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Active Directory | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for Active Directory tool selection, enumeration, relationship mapping, and attack-path analysis.**
