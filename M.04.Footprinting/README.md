# Footprinting - HTB Academy Guide

A practical CPTS module covering **infrastructure enumeration and service footprinting** across FTP/TFTP, SMB, NFS, DNS, IMAP/POP3, SNMP, MySQL, MSSQL, and IPMI.

The module focuses on identifying exposed services, gathering useful service information, discovering misconfigurations, and chaining findings during controlled HTB Academy labs.

---

## 📚 Module Overview

The notes follow a service-oriented footprinting workflow:

**Infrastructure Enumeration → Service Discovery → Service-Specific Enumeration → Misconfiguration Discovery → Credential / File Discovery → Access Validation → Attack-Path Analysis**

The module also contains **Easy and Medium footprinting labs** that demonstrate how multiple services and small information disclosures can be combined into a larger attack path.

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [Enumeration](./01.Enumeration.md) | Infrastructure enumeration layers, gateways, services, processes, privileges, and OS setup |
| 02 | [FTP / TFTP](./02.FTP_TFTP.md) | FTP/TFTP fundamentals, anonymous access, enumeration, configurations, and attack paths |
| 03 | [FTP Lab](./03.FTP_Lab.md) | FTP banner discovery and anonymous-access lab workflow |
| 04 | [SMB (Server Message Block)](./04.SMB%20(Server%20Message%20Block).md) | SMB architecture, shares, permissions, rpcclient, enumeration tools, and misconfigurations |
| 05 | [SMB Labs](./05.SMB_Labs.md) | SMB version, share, domain, path, and RPC enumeration labs |
| 06 | [NFS](./06.NFS.md) | NFS/RPC enumeration, exported shares, mounting, permissions, and lab exploitation |
| 07 | [DNS](./07.DNS.md) | DNS records, nameservers, version disclosure, zone transfers, and subdomain enumeration |
| 08 | [IMAP / POP3](./08.%20IMAP%20_%20POP3.md) | Mail protocols, ports, commands, service enumeration, TLS information, and mailbox access |
| 09 | [SNMP](./09.SNMP.md) | SNMP versions, community strings, OIDs, configuration risks, and enumeration tools |
| 10 | [MySQL](./10.MySQL.md) | MySQL architecture, configuration, Nmap enumeration, authentication, and database queries |
| 11 | [MSSQL](./11.MSSQL.md) | Microsoft SQL Server, authentication, Nmap/Metasploit enumeration, and database access |
| 12 | [IPMI](./12.IPMI.md) | BMC/IPMI architecture, UDP 623, default credentials, and IPMI 2.0 RAKP issues |
| 13 | [Footprinting Lab - Easy](./13.%20Footprinting%20Lab_Easy.md) | Chaining Nmap, FTP, exposed SSH keys, and SSH authentication |
| 14 | [Footprinting Lab - Medium](./14.Footprinting%20Lab%20-%20Medium.md) | Chaining NFS, SMB, RDP, and MSSQL findings to obtain credentials |

---

## 🧱 01. Enumeration

**[Enumeration](./01.Enumeration.md)** introduces infrastructure enumeration through six layers.

| Layer | Focus |
|---|---|
| Internet Presence | Domains, subdomains, vHosts, ASN, netblocks, IPs, cloud instances |
| Gateway | Firewalls, DMZ, IDS/IPS, EDR, proxies, NAC, VPN, Cloudflare |
| Accessible Services | Service type, functionality, configuration, port, version, interface |
| Processes | PID, processed data, tasks, source, destination |
| Privileges | Groups, users, permissions, restrictions, environment |
| OS Setup | OS type, patch level, network configuration, configuration files, sensitive files |

### Key Idea

Footprinting is not limited to ports and services. The notes organize information from the **internet-facing layer down toward internal processes, privileges, and OS configuration**.

---

## 📁 02. FTP / TFTP

**[FTP / TFTP](./02.FTP_TFTP.md)** covers two common file-transfer services.

### FTP

The notes cover:

- Application-layer protocol
- TCP 21 control channel
- TCP 20 data channel
- Username/password authentication
- File upload/download
- Active vs passive FTP
- Anonymous login
- FTP server banners
- FTP over SSL/TLS
- Nmap FTP scripts

### TFTP

The notes cover:

- UDP-based operation
- No authentication
- Read/write permissions
- Internal-network usage

### Important FTP Checks

- Anonymous access
- Weak credentials
- Writable directories
- Permission mistakes
- Sensitive files
- Links between FTP and web services

### Useful Enumeration

```bash
nmap -sV -p21 -sC -A <IP>
```

Banner grabbing:

```bash
nc <IP> 21
```

The notes also reference:

```bash
openssl s_client -connect <IP>:21 -starttls ftp
```

for TLS certificate and service information.

---

## 🧪 03. FTP Lab

**[FTP Lab](./03.FTP_Lab.md)** contains practical FTP questions.

The lab demonstrates:

- Using Nmap to identify FTP on port 21
- Recognizing when Nmap does not expose the complete banner
- Using Netcat to read the FTP banner
- Using Nmap's `banner` NSE script
- Testing anonymous login
- Retrieving a file from an accessible FTP server

Example banner enumeration:

```bash
nc <IP> 21
```

or:

```bash
nmap -p21 -sV --script=banner <IP>
```

---

## 🗂️ 04. SMB (Server Message Block)

**[SMB](./04.SMB%20(Server%20Message%20Block).md)** covers SMB as a network file and resource-sharing protocol.

### Core Concepts

- Client-server model
- File and folder sharing
- Printer sharing
- SMB over TCP 445
- Legacy NetBIOS ports 137–139
- SMB1 / CIFS
- SMB2 / SMB3
- Samba on Linux

### Samba Services

| Service | Role |
|---|---|
| `smbd` | File sharing |
| `nmbd` | NetBIOS support |

### Important Configuration Areas

`/etc/samba/smb.conf`

The notes highlight settings such as:

- `workgroup`
- `path`
- `browseable`
- `read only`
- `guest ok`

### Enumeration Commands

```bash
nmap -sC -sV -p139,445 <IP>
```

Anonymous share listing:

```bash
smbclient -N -L //<IP>
```

RPC enumeration:

```bash
rpcclient -U "" <IP>
```

Useful RPC commands include:

```text
srvinfo
enumdomains
enumdomusers
netshareenumall
queryuser RID
```

The notes also reference **smbmap, CrackMapExec, enum4linux-ng, and samrdump**.

---

## 🧪 05. SMB Labs

**[SMB Labs](./05.SMB_Labs.md)** provides practical questions around SMB enumeration.

The lab workflow includes:

1. Identify the SMB server version
2. Enumerate accessible shares
3. Connect to the discovered share
4. Retrieve files
5. Enumerate the server domain
6. Query share-specific information
7. Identify the full path of the share

RPC is used as an important source of additional SMB information, including domain and share details.

---

## 📦 06. NFS

**[NFS](./06.NFS.md)** covers Network File System enumeration and the lab workflow around exposed NFS exports.

### Common Ports

| Port | Service |
|---|---|
| 111 | RPC / rpcbind |
| 2049 | NFS |

### Enumeration

```bash
nmap -p111,2049 -sV -sC <IP>
```

NFS scripts:

```bash
sudo nmap --script nfs* <IP> -p111,2049
```

List exports:

```bash
showmount -e <IP>
```

### Mounting an Export

The notes use:

```bash
mkdir target-NFS
sudo mount -t nfs <IP>:/ ./target-NFS -o nolock
```

After mounting, the workflow is:

**Mount → Browse → Review Ownership → Inspect Files**

### Common Findings

- Broadly accessible exports
- Sensitive files
- SSH keys
- Unexpected permissions
- Anonymous-style access indicators such as `nobody:nogroup`

The notes also highlight configuration risks such as writable exports and `no_root_squash`.

---

## 🌐 07. DNS

**[DNS](./07.DNS.md)** covers DNS fundamentals and service enumeration.

### Important Records

| Record | Purpose |
|---|---|
| A | IPv4 address |
| AAAA | IPv6 address |
| MX | Mail server |
| NS | Nameserver |
| TXT | Text records / SPF / verification |
| CNAME | Alias |
| PTR | Reverse lookup |
| SOA | Zone information |

### Enumeration Techniques

Nameservers:

```bash
dig ns target.com @DNS_IP
```

Version disclosure:

```bash
dig CH TXT version.bind DNS_IP
```

Record query:

```bash
dig any target.com @DNS_IP
```

Zone transfer:

```bash
dig axfr target.com @DNS_IP
```

### Subdomain Enumeration

Manual approach:

```bash
for sub in $(cat wordlist.txt); do
  dig $sub.target.com @DNS_IP
done
```

Using `dnsenum`:

```bash
dnsenum --dnsserver DNS_IP --enum -f wordlist.txt target.com
```

### Important Concept

A successful AXFR request can expose the contents of a DNS zone, including hostnames and internal records.

---

## 📧 08. IMAP / POP3

**[IMAP / POP3](./08.%20IMAP%20_%20POP3.md)** covers email retrieval protocols and their enumeration.

### Ports

| Protocol | Standard | TLS |
|---|---:|---:|
| IMAP | 143 | 993 |
| POP3 | 110 | 995 |

### IMAP

The notes cover:

- Server-side mailbox storage
- Folder management
- Multi-client synchronization
- Mailbox listing
- `LOGIN`
- `LIST`
- `SELECT`
- `FETCH`

### POP3

The notes cover:

- Message retrieval
- Listing messages
- Message deletion
- `USER`
- `PASS`
- `LIST`
- `RETR`
- `DELE`

### Mail-Service Enumeration

```bash
sudo nmap <IP> -sV -p110,143,993,995 -sC
```

IMAPS interaction with cURL:

```bash
curl -k 'imaps://<IP>' --user user:password
```

TLS/service inspection:

```bash
openssl s_client -connect <IP>:pop3s
```

```bash
openssl s_client -connect <IP>:imaps
```

The notes emphasize the value of mail services for identifying software, certificates, capabilities, and potentially sensitive mailbox content when valid credentials are available.

---

## 📡 09. SNMP

**[SNMP](./09.SNMP.md)** covers network and device-management information exposure.

### Ports

| Port | Purpose |
|---|---|
| UDP 161 | SNMP communication |
| UDP 162 | SNMP traps |

### SNMP Versions

| Version | Security Model |
|---|---|
| SNMPv1 | No encryption/authentication |
| SNMPv2c | Community-string based |
| SNMPv3 | Authentication and encryption |

### Important Concepts

- Community strings
- MIB
- OID
- SNMP traps
- Configuration files
- Read/write access

Common strings referenced by the notes:

```text
public
private
```

### Enumeration Tools

**snmpwalk · onesixtyone · braa**

Example:

```bash
snmpwalk -v2c -c public <IP>
```

Community-string discovery:

```bash
onesixtyone -c /opt/useful/seclists/Discovery/SNMP/snmp.txt <IP>
```

Fast OID enumeration:

```bash
braa public@<IP>:.1.3.6.*
```

SNMP can reveal system information such as OS details, hostnames, users, installed software, services, and contact information.

---

## 🗄️ 10. MySQL

**[MySQL](./10.MySQL.md)** introduces MySQL as a relational database system commonly used by web applications.

### Default Port

**TCP 3306**

### Common Stack

**LAMP → Linux + Apache + MySQL + PHP**

**LEMP → Linux + Nginx + MySQL + PHP**

### Configuration

The notes review:

- Service user
- Password configuration
- Port
- Data directory
- `secure_file_priv`

### Nmap Enumeration

```bash
sudo nmap <IP> -sV -sC -p3306 --script mysql*
```

Potential information includes:

- MySQL version
- Authentication behavior
- Empty passwords
- User information
- Vulnerability indicators

### MySQL Client

```bash
mysql -u root -h <IP>
```

With password:

```bash
mysql -u root -pPassword -h <IP>
```

### Useful SQL Commands

```sql
show databases;
use mysql;
show tables;
show columns from user;
select * from user;
select version();
```

The notes also cover the `information_schema`, `performance_schema`, and `sys` databases.

---

## 🗃️ 11. MSSQL

**[MSSQL](./11.MSSQL.md)** covers Microsoft SQL Server and its relationship with Windows and Active Directory environments.

### Default Port

**TCP 1433**

### Common Access Methods

- SSMS
- mssql-cli
- PowerShell
- HeidiSQL
- SQLPro
- `mssqlclient.py`

### Nmap Enumeration

The notes include the use of MSSQL NSE scripts for gathering:

- Version
- Hostname
- Instance name
- Named pipes
- Authentication information
- Database-access details

### Metasploit

The notes demonstrate the MSSQL ping scanner:

```
auxiliary/scanner/mssql/mssql_ping
```

### Impacket Client

```bash
python3 mssqlclient.py Administrator@<IP> -windows-auth
```

### Database Enumeration

After authentication, the notes use:

```sql
select name from sys.databases;
```

The section highlights the importance of MSSQL in Windows environments because database compromise can expose sensitive data and potentially support lateral-movement or broader domain-impact scenarios.

---

## 🖥️ 12. IPMI

**[IPMI](./12.IPMI.md)** covers the **Intelligent Platform Management Interface** and hardware-level remote administration.

### Purpose

IPMI can support:

- Remote server management
- Hardware monitoring
- Power control
- BIOS management
- Recovery
- Out-of-band administration

### Important Component

**BMC — Baseboard Management Controller**

The notes emphasize that BMC compromise can provide a level of control comparable to physical server access.

### Port

**UDP 623**

### Enumeration

```bash
sudo nmap -sU --script ipmi-version -p 623 <target>
```

Metasploit:

```
auxiliary/scanner/ipmi/ipmi_version
```

### Security Topics

The notes cover:

- Vendor defaults
- Default credentials
- IPMI authentication
- IPMI 2.0 RAKP-related hash disclosure
- Offline password cracking
- IPMI hash-dumping workflows

IPMI exposure is especially important because the interface operates outside the normal operating-system security boundary.

---

## 🧪 13. Footprinting Lab — Easy

**[Footprinting Lab - Easy](./13.%20Footprinting%20Lab_Easy.md)** demonstrates how several service-level discoveries can be chained together.

### Lab Flow

```text
Nmap
  ↓
FTP Discovery
  ↓
Alternate FTP Service
  ↓
Credentialed FTP Access
  ↓
.ssh Directory
  ↓
Exposed id_rsa
  ↓
SSH Key Authentication
  ↓
User Shell
  ↓
flag.txt
```

### Core Lessons

- Do not stop after identifying one service
- Check alternate service ports
- Review accessible directories and files
- Sensitive SSH keys can completely change the attack path
- SSH password authentication being disabled does not protect an account when its private key is exposed

The lab connects service enumeration with credential and file discovery rather than relying on a direct software exploit.

---

## 🧪 14. Footprinting Lab — Medium

**[Footprinting Lab - Medium](./14.Footprinting%20Lab%20-%20Medium.md)** demonstrates a longer multi-service attack path.

### Lab Flow

```text
Nmap
  ↓
SMB / NFS / RDP / WinRM Discovery
  ↓
NFS Export Enumeration
  ↓
Mount Exposed Share
  ↓
Credentials in Support Files
  ↓
SMB Authentication
  ↓
SQL Credentials in SMB Share
  ↓
RDP Access
  ↓
SQL Server Access
  ↓
HTB User Credential Discovery
```

### Main Security Lessons

- Anonymous NFS access can expose sensitive files
- Credentials stored in support tickets create a serious information-disclosure path
- Credential reuse can connect otherwise separate services
- Sensitive SQL credentials stored on accessible shares increase impact
- Weak internal segmentation can allow findings to be chained across SMB, NFS, RDP, and MSSQL

This lab shows that a complete compromise path can emerge from **multiple configuration and credential-management weaknesses without requiring a traditional software exploit**.

---

## 🧭 Footprinting Workflow

A practical CPTS workflow based on the module is:

### 1. Identify Infrastructure

Map domains, IP addresses, gateways, accessible interfaces, and network boundaries.

### 2. Scan Exposed Services

Use Nmap to identify ports and service versions.

### 3. Select Service-Specific Enumeration

Choose the relevant approach:

**FTP · SMB · NFS · DNS · IMAP/POP3 · SNMP · MySQL · MSSQL · IPMI**

### 4. Review Configurations & Access

Look for:

- Anonymous access
- Weak credentials
- Broad permissions
- Exposed shares
- Sensitive files
- Service banners
- Default configurations
- Authentication weaknesses

### 5. Correlate Findings

Connect information across services:

**Credentials → Shares → SSH → RDP → Databases → User Accounts**

### 6. Validate the Attack Path

Confirm that each step is actually possible within the lab or authorized engagement.

---

## 🧠 CPTS Quick Reference

| Service | Port | Primary Enumeration |
|---|---|---|
| FTP | 21/TCP | Nmap, FTP client, Netcat |
| TFTP | UDP | TFTP client/server |
| SMB | 445/TCP | Nmap, smbclient, rpcclient |
| NetBIOS | 137–139 | Nmap / SMB tools |
| NFS | 2049/TCP | Nmap, showmount, mount |
| RPC | 111/TCP | Nmap / RPC tools |
| DNS | 53/TCP/UDP | dig, dnsenum, Nmap |
| IMAP | 143/TCP | Nmap, cURL |
| IMAPS | 993/TCP | cURL, OpenSSL |
| POP3 | 110/TCP | Nmap, protocol clients |
| POP3S | 995/TCP | OpenSSL |
| SNMP | 161/UDP | snmpwalk, onesixtyone, braa |
| MySQL | 3306/TCP | Nmap, mysql client |
| MSSQL | 1433/TCP | Nmap, Metasploit, mssqlclient |
| IPMI | 623/UDP | Nmap, Metasploit |

---

## 🛠️ Tools Covered

**Nmap · Netcat · FTP · smbclient · rpcclient · smbmap · CrackMapExec · enum4linux-ng · samrdump · showmount · mount · dig · dnsenum · cURL · OpenSSL · snmpwalk · onesixtyone · braa · mysql · Metasploit · mssqlclient.py · Hashcat**

---

## 🧠 Key Learning Points

### Enumeration Is the Foundation

Service versions, banners, shares, exports, mail capabilities, database information, and management interfaces can all provide valuable clues.

### Misconfiguration Can Be More Important Than a CVE

The Easy and Medium labs demonstrate attack paths built primarily from **exposed services, weak access controls, and insecure credential storage**.

### Think in Chains

A single low-impact finding may become critical when combined with another service:

**NFS Exposure → Credentials → SMB → RDP → MSSQL**

### Validate Before Exploiting

Not every version or scanner result indicates a real vulnerability. The notes repeatedly emphasize enumeration and verification before proceeding.

---

## 📖 Recommended Study Order

Study this module in this sequence:

**1. Enumeration → 2. FTP/TFTP → 3. SMB → 4. NFS → 5. DNS → 6. IMAP/POP3 → 7. SNMP → 8. MySQL → 9. MSSQL → 10. IPMI → 11. Easy Lab → 12. Medium Lab**

The labs should be treated as the practical stage where the individual service-enumeration techniques are combined into complete attack paths.

---

## ⚠️ Responsible Use

These techniques are intended for:

**HTB Academy · CPTS Preparation · CTFs · Security Labs · Authorized Penetration Testing**

Only enumerate, authenticate to, mount, scan, or otherwise interact with systems that are explicitly within your authorized scope.

Be especially careful with:

- Network scanning
- Anonymous-access testing
- Credential testing
- Database interaction
- IPMI management interfaces
- Password cracking
- Firewall or network-control testing

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for footprinting, service enumeration, misconfiguration discovery, and multi-service attack-path analysis.**
