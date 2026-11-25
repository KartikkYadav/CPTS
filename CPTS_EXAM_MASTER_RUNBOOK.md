# CPTS Exam Master Runbook — Start to Finish

> **Purpose:** One practical, exam-day workflow for approaching a CPTS-style penetration-testing assessment. It consolidates the methodology and commands from this repository into an ordered runbook.
>
> **Scope and integrity:** Use only targets, networks, accounts, and techniques explicitly permitted by the exam rules. Follow the current HTB exam instructions over any older note in this file. Do not attack infrastructure outside the assigned scope, disrupt services, alter evidence, or perform destructive actions.
>
> **Important:** This is a methodology and reference, not a guarantee that every exam has the same network, targets, scoring, time limit, or required report format. Use the actual exam portal and rules of engagement as the source of truth.

---

## Contents

1. [Exam mindset and workflow](#1-exam-mindset-and-workflow)
2. [Before starting](#2-before-starting)
3. [Build your workspace](#3-build-your-workspace)
4. [Initial target discovery](#4-initial-target-discovery)
5. [TCP and UDP scanning](#5-tcp-and-udp-scanning)
6. [Service-by-service enumeration](#6-service-by-service-enumeration)
7. [Web application methodology](#7-web-application-methodology)
8. [Vulnerability assessment and exploit research](#8-vulnerability-assessment-and-exploit-research)
9. [Credentials and password testing](#9-credentials-and-password-testing)
10. [Initial access and shell handling](#10-initial-access-and-shell-handling)
11. [Post-exploitation enumeration](#11-post-exploitation-enumeration)
12. [Linux privilege escalation](#12-linux-privilege-escalation)
13. [Windows privilege escalation](#13-windows-privilege-escalation)
14. [Active Directory methodology](#14-active-directory-methodology)
15. [Pivoting and internal network enumeration](#15-pivoting-and-internal-network-enumeration)
16. [File transfer](#16-file-transfer)
17. [Metasploit and supporting tools](#17-metasploit-and-supporting-tools)
18. [Troubleshooting](#18-troubleshooting)
19. [Evidence and final reporting](#19-evidence-and-final-reporting)
20. [Time management and final checks](#20-time-management-and-final-checks)
21. [Quick command reference](#21-quick-command-reference)
22. [Exam-day checklist](#22-exam-day-checklist)

---

## 1. Exam mindset and workflow

The most important principle is:

**Do not run tools randomly. Enumerate, understand the evidence, and choose the next action based on what you discovered.**

Use this loop throughout the assessment:

```text
Scope
  ↓
Discover hosts
  ↓
Scan ports
  ↓
Identify services and versions
  ↓
Enumerate each service
  ↓
Form a vulnerability hypothesis
  ↓
Validate safely
  ↓
Obtain authorized access
  ↓
Enumerate the compromised host
  ↓
Identify a privilege-escalation path
  ↓
Validate objective and collect evidence
  ↓
Continue through discovered systems
  ↓
Write and verify the report
```

### Rules to keep in mind

- An **open port is not a vulnerability**. It is an entry point for further enumeration.
- A version banner is a clue, not proof that a CVE applies.
- A scanner result is not automatically a confirmed finding.
- A successful login is not the end of enumeration.
- A low-privileged shell is not proof of administrator or root access.
- A suspicious permission is not exploitable until you identify how it crosses a security boundary.
- Credentials found on one host may be useful elsewhere, but validate them only where permitted.
- Preserve exact commands, requests, outputs, timestamps, and screenshots as you work.

---

## 2. Before starting

### 2.1 Read the exam instructions first

Before scanning, identify and record:

- Exam start and end time.
- Authorized target IP addresses and CIDR ranges.
- Any excluded hosts, ports, services, or techniques.
- Whether external internet access is available.
- Whether pivoting is permitted and which networks are in scope.
- Rules for brute-force testing, password spraying, denial-of-service, social engineering, and destructive testing.
- Required flags, proof files, screenshots, report format, and submission process.
- Whether the exam requires a specific VPN or connection method.

> **Never assume the rules from a previous HTB exam attempt apply to the current attempt.**

### 2.2 Prepare a target and evidence table

Create a simple table before testing:

| Target | Hostname / domain | Open ports | Access level | Status | Notes |
|---|---|---|---|---|---|
| Target 1 | TBD | TBD | Unknown | Not started | |
| Target 2 | TBD | TBD | Unknown | Not started | |

Keep a separate credential register in an encrypted/private location:

| Username | Password / key reference | Domain / host | Source | Validated? |
|---|---|---|---|---|
| | | | | |

Do not place real passwords, private keys, or tokens in a public repository or unredacted report.

---

## 3. Build your workspace

### 3.1 Create an organized directory

Example on Kali Linux:

```bash
mkdir -p ~/CPTS-Exam/{scans,loot,transfers,notes,screenshots,web,credentials}
cd ~/CPTS-Exam
```

The folders are used for:

- `scans/`: Nmap and scanner output.
- `loot/`: collected files and authorized evidence.
- `transfers/`: files moved between lab systems.
- `notes/`: timeline, commands, findings, and hypotheses.
- `screenshots/`: visual evidence.
- `web/`: HTTP requests, responses, and web enumeration output.
- `credentials/`: sensitive credential material; protect this directory.

Protect sensitive material:

```bash
chmod 700 ~/CPTS-Exam/credentials
```

### 3.2 Set target variables

Use actual values from the exam portal. Do not copy example IPs blindly.

```bash
export RHOST=TARGET_IP
export LHOST=YOUR_VPN_IP
```

Check your VPN interface and routes:

```bash
ip -br a
ip route
```

Confirm the expected interface and address before starting listeners or callbacks.

### 3.3 Start a written log

```bash
script -a ~/CPTS-Exam/notes/terminal-session.log
```

This records terminal activity. Stop the recording with:

```text
exit
```

Terminal logs can contain secrets. Store them privately and redact them before sharing.

---

## 4. Initial target discovery

### 4.1 Confirm connectivity

Use the target IP and network details provided by the exam.

```bash
ip route get "$RHOST"
ping -c 3 "$RHOST"
```

A failed ping does **not** mean the host is down. ICMP may be blocked.

### 4.2 Discover hosts in an authorized subnet

Only use the CIDR block explicitly included in the exam scope.

```bash
fping -asgq AUTHORIZED_CIDR
```

Save discovered IP addresses to a file, for example `hosts.txt`, and keep the original scope list unchanged.

### 4.3 Record hostnames and network clues

Useful sources include:

- DNS records and reverse lookups.
- Nmap service banners.
- TLS certificates.
- SMB and LDAP information.
- Web virtual hosts.
- Application configuration files discovered during authorized access.

Do not infer that an IP is in scope merely because it is reachable from a compromised host.

---

## 5. TCP and UDP scanning

### 5.1 Initial TCP scan

```bash
nmap -sC -sV -oN scans/initial.txt "$RHOST"
```

| Option | Meaning |
|---|---|
| `-sC` | Run the default NSE script set |
| `-sV` | Detect service versions |
| `-oN` | Save normal-format output |

### 5.2 Scan all TCP ports

```bash
nmap -p- --open -T4 -oN scans/all-tcp.txt "$RHOST"
```

Review the results, then perform detailed enumeration of the discovered ports:

```bash
nmap -sC -sV -p PORT_LIST -oN scans/detailed-tcp.txt "$RHOST"
```

Replace `PORT_LIST` with the exact ports discovered. Do not use the example list from another target without checking your own scan.

### 5.3 UDP scan

```bash
sudo nmap -sU --top-ports 100 -sV -oN scans/udp-top100.txt "$RHOST"
```

UDP scans can be slow and may report `open|filtered`. Interpret results carefully and focus follow-up checks on services indicated by evidence.

### 5.4 Useful Nmap options

| Option | Purpose |
|---|---|
| `-sS` | TCP SYN scan |
| `-sT` | TCP connect scan |
| `-sU` | UDP scan |
| `-sV` | Service/version detection |
| `-sC` | Default NSE scripts |
| `-O` | OS detection |
| `-A` | Aggressive detection bundle; can be noisy |
| `-p-` | All TCP ports |
| `-p PORTS` | Selected ports |
| `-Pn` | Skip host discovery |
| `-n` | Disable DNS resolution |
| `--open` | Show open ports |
| `-oA NAME` | Save in normal, grepable, and XML formats |
| `--reason` | Show why Nmap assigned a port state |

### 5.5 Understand port states

| State | Meaning |
|---|---|
| `open` | A service accepted or responded to a connection |
| `closed` | The host responded but no service is listening |
| `filtered` | Nmap cannot determine whether the port is open because filtering prevents a useful response |
| `open|filtered` | Nmap cannot distinguish an open service from filtering, commonly in UDP scans |

For each open port, record the service, version, banner, default scripts, and next enumeration step.

---

## 6. Service-by-service enumeration

Use the discovered ports, not assumptions. The following table is a decision aid.

| Port | Service | First checks |
|---:|---|---|
| 21 | FTP | Anonymous access, files, server version |
| 22 | SSH | Version, authentication methods, usernames, discovered keys |
| 25, 465, 587 | SMTP | Banner, EHLO capabilities, recipient behavior |
| 53 | DNS | A, AAAA, MX, NS, TXT, AXFR where appropriate |
| 80, 443 | HTTP/S | Technologies, routes, virtual hosts, forms, APIs |
| 110, 995 | POP3 | Banner, TLS, authorized mailbox access |
| 111, 2049 | RPC/NFS | Exports and accessible mounts |
| 135, 139, 445 | Windows RPC/SMB | Shares, domain information, permissions |
| 143, 993 | IMAP | Banner, TLS, authorized mailbox access |
| 161/UDP | SNMP | Version, community configuration, system information |
| 3306 | MySQL | Version, authentication, databases and privileges |
| 3389 | RDP | NLA, domain/local username format, access |
| 1433 | MSSQL | Version, authentication, database permissions |
| 5985, 5986 | WinRM | HTTP/HTTPS management and authorized login |
| 8080, 8443 | Alternate web | Application and virtual-host enumeration |

### 6.1 FTP — TCP 21

Enumerate:

```bash
nmap -sC -sV -p 21 "$RHOST"
ftp "$RHOST"
```

Check anonymous access only if permitted. Within an interactive FTP client:

```text
help
pwd
ls -la
cd DIRECTORY
get FILE
binary
bye
```

Questions:

- Is anonymous access enabled?
- Are files or backups exposed?
- Are there configuration files or credentials?
- Does the banner identify an old or unusual product?
- Is there a separate FTP management web interface?

If directory listing hangs, test passive/active mode and consider whether the FTP data channel is blocked. FTP uses a separate data connection; it may behave differently through a proxy.

### 6.2 SSH — TCP 22

```bash
nmap -sC -sV -p 22 "$RHOST"
ssh USERNAME@"$RHOST"
```

When a private key is discovered and authorized for testing:

```bash
chmod 600 id_rsa
ssh -i id_rsa USERNAME@"$RHOST"
```

Verbose troubleshooting:

```bash
ssh -vvv -i id_rsa USERNAME@"$RHOST"
```

Remember:

- A host-key fingerprint is not a private key.
- A key may be encrypted and require a passphrase.
- A key must be associated with the correct username.
- `Permission denied (publickey)` usually means the server did not accept that key for that account.

### 6.3 SMB — TCP 139/445

```bash
nmap -sC -sV -p 139,445 "$RHOST"
smbclient -L "//$RHOST" -N
smbmap -H "$RHOST"
```

Connect to a discovered share:

```bash
smbclient "//$RHOST/SHARE" -U USERNAME
```

Inside `smbclient`:

```text
ls
cd DIRECTORY
get FILE
mget *
exit
```

Look for guest/anonymous access, readable shares, scripts, backups, configuration files, and excessive write permissions. A share being visible does not mean its files are readable.

### 6.4 DNS — TCP/UDP 53

```bash
dig @"$RHOST" DOMAIN A
dig @"$RHOST" DOMAIN MX
dig @"$RHOST" DOMAIN NS
dig @"$RHOST" DOMAIN TXT
dig axfr DOMAIN @"$RHOST"
```

A successful zone transfer may reveal hostnames and infrastructure. AXFR normally fails when correctly restricted.

### 6.5 NFS — TCP 2049 / RPC 111

```bash
nmap -sC -sV -p 111,2049 "$RHOST"
showmount -e "$RHOST"
```

If an export is intentionally accessible and mounting is in scope:

```bash
sudo mkdir -p /mnt/nfs
sudo mount -t nfs "$RHOST:/EXPORT" /mnt/nfs
ls -la /mnt/nfs
sudo umount /mnt/nfs
```

Replace `/EXPORT` with an actual export path.

### 6.6 SNMP — UDP 161

```bash
sudo nmap -sU -sV -p 161 "$RHOST"
snmpwalk -v2c -c COMMUNITY "$RHOST" 1.3.6.1.2.1.1
```

A permitted SNMP query may disclose hostname, system description, interfaces, and network information. SNMPv1/v2c community strings are not equivalent to modern SNMPv3 authentication and privacy.

### 6.7 SMTP — TCP 25/465/587

```bash
nmap -sC -sV -p 25,465,587 "$RHOST"
nc -nv "$RHOST" 25
```

At the SMTP prompt:

```text
EHLO test.local
HELP
QUIT
```

Use a TLS-capable client for TLS-enabled services. Recipient validation and user enumeration depend on server behavior and may be intentionally restricted.

### 6.8 MySQL — TCP 3306

```bash
nmap -sC -sV -p 3306 "$RHOST"
mysql -h "$RHOST" -P 3306 -u USERNAME -p
```

Once authenticated, if permitted:

```sql
SELECT VERSION();
SELECT USER(), CURRENT_USER();
SHOW DATABASES;
SHOW GRANTS;
```

After selecting an accessible database:

```sql
USE DATABASE_NAME;
SHOW TABLES;
DESCRIBE TABLE_NAME;
SELECT * FROM TABLE_NAME LIMIT 20;
```

Review `secure_file_priv` and effective database privileges before drawing conclusions about filesystem operations. Database access does not automatically imply operating-system access.

### 6.9 MSSQL — TCP 1433

```bash
nmap -sC -sV -p 1433 "$RHOST"
```

With authorized credentials and a compatible Impacket installation:

```bash
impacket-mssqlclient DOMAIN/USERNAME:PASSWORD@"$RHOST"
```

Basic SQL checks:

```sql
SELECT @@VERSION;
SELECT SYSTEM_USER;
SELECT USER_NAME();
SELECT name FROM sys.databases;
```

A SQL login is not automatically a Windows administrator. Enumerate effective database permissions and any relevant linked-server or trust configuration.

### 6.10 RDP — TCP 3389

```bash
nmap -sC -sV -p 3389 "$RHOST"
xfreerdp /v:TARGET_IP /u:USERNAME
```

Check whether the account is authorized for remote interactive login, the correct local/domain username format, and whether Network Level Authentication is required.

### 6.11 WinRM — TCP 5985/5986

```bash
nmap -sC -sV -p 5985,5986 "$RHOST"
```

With permitted credentials:

```bash
evil-winrm -i "$RHOST" -u USERNAME -p 'PASSWORD'
```

A valid Windows credential may still lack WinRM permissions.

### 6.12 POP3 / IMAP

```bash
nmap -sC -sV -p 110,143,993,995 "$RHOST"
nc -nv "$RHOST" 110
```

For POP3, common commands include `USER`, `PASS`, `STAT`, `LIST`, `RETR`, and `QUIT`. Use only authorized credentials. Mailboxes can contain reset messages, internal hostnames, configuration information, and other sensitive artifacts.

---

## 7. Web application methodology

Web testing should be methodical. Capture a normal request first, change one variable at a time, and compare the response.

### 7.1 Identify the application

```bash
curl -i "http://$RHOST/"
whatweb "http://$RHOST/"
```

For HTTPS:

```bash
curl -k -i "https://$RHOST/"
```

Use `-k` only when certificate verification prevents testing a lab service.

Check:

- HTTP status and redirects.
- Server and technology hints.
- TLS certificate and hostnames.
- Cookies and authentication.
- `robots.txt`, source code, forms, API documentation, and JavaScript references.
- Alternate ports and virtual hosts.

### 7.2 Directory and file discovery

Use FFUF/Gobuster with a lab-appropriate wordlist and a rate suitable for the exam rules.

```bash
ffuf -u "http://$RHOST/FUZZ"   -w /usr/share/seclists/Discovery/Web-Content/common.txt
```

Do not treat every response as a real directory. Filter or compare by status, size, words, lines, redirects, and content. Check discovered routes manually.

### 7.3 Virtual-host discovery

When a hostname is suspected, test it explicitly:

```bash
curl -i -H 'Host: app.example.local' "http://$RHOST/"
```

For HTTPS, the hostname may also affect TLS SNI. Prefer using the hostname in the URL and mapping it to the target IP where possible.

### 7.4 Burp Suite workflow

1. Configure the browser to use Burp Proxy.
2. Browse the application normally.
3. Capture the relevant HTTP request.
4. Send it to Repeater.
5. Save the untouched request as a baseline.
6. Change one parameter, header, method, identifier, or body field at a time.
7. Compare status, response body, length, headers, redirects, and timing.
8. Record the exact request and response supporting a conclusion.

Do not assume a changed status code alone proves a vulnerability.

### 7.5 Web attack decision guide

| Area | What to test | Evidence to establish |
|---|---|---|
| Authentication | Login, recovery, session behavior, rate limits | A reproducible authentication or session weakness |
| SQL injection | Parameters, forms, headers, API bodies | Input changes query behavior and produces verified impact |
| XSS | Reflected, stored, and DOM data flows | Untrusted input executes in the intended browser context |
| File inclusion / traversal | File and template parameters | Access to an unintended file or inclusion behavior |
| File upload | Server validation, storage path, content handling | Upload acceptance plus independently verified processing/impact |
| Command injection | Features that invoke system utilities | Input is interpreted as an OS command |
| IDOR / access control | Object IDs and roles | A user accesses or modifies an object they are not authorized to access |
| HTTP verb tampering | Supported methods and authorization | A protected operation succeeds through an improperly checked method |
| XXE | XML parsers and XML-based upload formats | Parser resolves prohibited external entities |
| SSRF | Server-side URL or destination features | The server reaches an unauthorized destination |
| Common applications | WordPress, Joomla, Drupal, Tomcat, Jenkins, Splunk, PRTG, GitLab | Version/configuration and an applicable, validated weakness |

### 7.6 SQL injection workflow

1. Identify a candidate input.
2. Capture a baseline request and response.
3. Test whether the input affects query behavior.
4. Identify the DBMS where possible.
5. Determine column count and reflected columns for in-band UNION cases.
6. Enumerate only information needed to establish impact.
7. Consider file access only when privileges and scope support it.
8. Validate the result and document the exact request.

Useful SQL concepts in your notes:

- Query logic and comments.
- UNION column-count compatibility.
- DBMS fingerprinting.
- `INFORMATION_SCHEMA` tables such as `SCHEMATA`, `TABLES`, and `COLUMNS`.
- MySQL functions such as `VERSION()` and `DATABASE()`.
- File operations depend on database privileges, server configuration, and OS permissions.

### 7.7 SQLMap request handling

For a simple lab GET request:

```bash
sqlmap -u 'http://TARGET/page.php?id=1'
```

For POST data:

```bash
sqlmap -u 'http://TARGET/' --data='uid=1&name=test' -p uid
```

For a complex authenticated request, save the exact request from Burp to a file and use:

```bash
sqlmap -r request.txt
```

Review SQLMap output carefully. A heuristic message such as “might be injectable” is not the same as a confirmed injection point. Do not run destructive or data-modifying options unless the rules explicitly permit them and they are necessary.

### 7.8 LFI / RFI

Reason about the final path the application constructs:

- Which parameter chooses the file?
- Does the application add a directory prefix or extension?
- Does it normalize or decode the path?
- Can a safe local file be read?
- Is PHP source executed or disclosed?
- Are PHP wrappers enabled?
- Is there a separate upload or log/session interaction?

PHP filters may help inspect source in applicable lab configurations. LFI does not automatically imply RCE; runtime, function, configuration, file content, and permissions determine impact.

### 7.9 File uploads

A successful upload is not proof of execution. Establish each stage independently:

1. Is the request accepted?
2. Does server-side validation enforce the intended type?
3. Where is the file stored?
4. Can it be retrieved?
5. How does the server/browser process it?
6. Is it rendered, parsed, downloaded, or executed?
7. What is the minimal safe proof of impact?

Review extension allowlists, content validation, MIME handling, generated filenames, file size, upload directory permissions, and server handler mappings. Consider non-code impacts such as stored XSS, XXE, SSRF, parser flaws, and resource exhaustion.

### 7.10 Command injection

Look for functionality that invokes operating-system commands, such as diagnostic, file-management, conversion, or network tools. Capture the normal request, identify the input that reaches the command, and use the minimum harmless proof permitted by the lab. If filtering is present, identify whether it is applied by the application, WAF, or shell. Do not jump directly to complex obfuscation; first prove the trust boundary and document the exact working behavior.

### 7.11 Common applications

When identifying WordPress, Joomla, Drupal, Tomcat, Jenkins, Splunk, PRTG, or GitLab:

- Confirm the product, version, and exposed features.
- Identify unauthenticated versus authenticated functionality.
- Check configuration and access controls.
- Research only vulnerabilities matching the exact product/build and configuration.
- Validate the weakness using the minimum non-destructive proof.
- Do not assume a product name or banner alone confirms exploitability.

---

## 8. Vulnerability assessment and exploit research

### 8.1 Scanner workflow

Your notes cover Nessus and OpenVAS/GVM. The general process is:

1. Confirm scope and scan permission.
2. Define targets.
3. Select an appropriate scan profile.
4. Configure port coverage.
5. Configure credentials only when authorized.
6. Run the scan.
7. Review plugin/finding evidence.
8. Validate important findings manually.
9. Record false positives and limitations.
10. Report the actual impact and remediation.

### 8.2 Authenticated versus unauthenticated scanning

| Scan type | Visibility |
|---|---|
| Unauthenticated | Primarily network-visible services and exposures |
| Authenticated | Deeper software, patch, configuration, and local vulnerability information |

Credentialed scans can provide better visibility, but credentials must be valid and supplied within scope.

### 8.3 Researching a public exploit

Use the sequence:

```text
Service and version identified
       ↓
Confirm exact product/build/configuration
       ↓
Search vendor advisory and reliable references
       ↓
Review exploit prerequisites and limitations
       ↓
Check whether the target is actually affected
       ↓
Validate with a safe proof
       ↓
Exploit only if explicitly allowed and necessary
       ↓
Record exact evidence and impact
```

Useful local search:

```bash
searchsploit PRODUCT VERSION
```

A public proof of concept may be unreliable, destructive, or written for a different configuration. Read it before use.

---

## 9. Credentials and password testing

### 9.1 Credential sources

After authorized access, inspect likely sources:

- Configuration files and environment files.
- Application backups and deployment scripts.
- Shell history.
- SSH keys and known-hosts data.
- Readable network shares.
- Database configuration.
- Email and reset workflows.
- Memory/credential stores only where specifically permitted.
- Captured traffic and logs supplied by the lab.

Treat recovered credentials as sensitive. Keep a record of where each one came from.

### 9.2 Offline password recovery

Your notes cover John the Ripper and Hashcat.

Typical workflow:

1. Identify the hash or protected-file type.
2. Extract a supported hash representation using the appropriate converter when needed.
3. Select a compatible format/mode.
4. Use an approved wordlist or targeted candidates.
5. Review results and verify the recovered password only in scope.

Examples:

```bash
john --wordlist=/usr/share/wordlists/rockyou.txt hashes.txt
```

```bash
hashcat -a 0 -m HASH_MODE hashes.txt /usr/share/wordlists/rockyou.txt
```

Common Hashcat modes in your notes:

| Hash type | Mode |
|---|---:|
| MD5 | 0 |
| SHA-1 | 100 |
| SHA-256 | 1400 |
| SHA-512 | 1700 |
| NTLM | 1000 |
| Kerberos 5 TGS-REP etype 23 | 13100 |
| Kerberos AS-REP etype 23 | 18200 |

Always confirm the actual hash type before choosing a mode. These values do not cover every algorithm or encryption format.

### 9.3 Online authentication testing

Online brute force, password spraying, and credential-stuffing tests can lock accounts and generate alerts. Perform them only when explicitly permitted, after reviewing the password policy, lockout threshold, rate limits, and rules of engagement. Never assume a high thread count is safe.

---

## 10. Initial access and shell handling

### 10.1 Before using a shell or payload

- Confirm the vulnerability and required prerequisites.
- Confirm that the technique is allowed by the current rules.
- Check that the callback/listener address is the correct VPN address.
- Check routing and firewall behavior.
- Prefer a minimal proof over unnecessary payloads.
- Record how access was obtained and the account context.

### 10.2 Bind versus reverse shell

| Type | Connection direction |
|---|---|
| Bind shell | Tester connects to a listener on the target |
| Reverse shell | Target connects back to the tester |

Either depends on the network path, firewall, NAT, and execution context. Do not assume a callback will work simply because a payload was generated.

### 10.3 Identify the shell context immediately

On Linux:

```bash
whoami
id
hostname
pwd
uname -a
```

On Windows:

```powershell
whoami
hostname
whoami /priv
whoami /groups
ipconfig /all
```

Record the user, host, domain, OS, current directory, and privileges before doing anything else.

### 10.4 Stabilize an interactive Linux shell

If a permitted lab shell is non-interactive, Python may be available:

```bash
python3 -c 'import pty; pty.spawn("/bin/bash")'
```

A TTY improves interaction with terminal-based programs. It does not grant additional privileges.

---

## 11. Post-exploitation enumeration

After initial access, stop and enumerate the machine before choosing an escalation route.

### 11.1 Universal questions

- What user am I?
- What host am I on?
- Is it domain joined?
- What OS and version is running?
- What network interfaces and routes exist?
- What processes and services run with elevated privileges?
- What software versions are installed?
- What files, directories, shares, and credentials can I access?
- What scheduled jobs and service configurations exist?
- Which local permissions can I influence?
- Are there other in-scope hosts reachable from this system?

### 11.2 Capture the environment

Linux:

```bash
whoami
id
hostname
ip a
ip route
ps aux
ss -lntp
```

Windows:

```powershell
whoami
hostname
ipconfig /all
route print
tasklist /v
net user
net localgroup
```

Do not run every command blindly. Choose follow-up enumeration based on the system and permissions.

---

## 12. Linux privilege escalation

The goal is to identify a specific, evidence-supported route from the current account to a more privileged account, usually `root`.

### 12.1 OS, kernel, packages

```bash
cat /etc/os-release
uname -a
uname -r
dpkg -l
```

For RPM-based systems:

```bash
rpm -qa
```

Check versions against trusted advisories. A kernel exploit can crash or destabilize the host, so do not execute one just because the kernel looks old.

### 12.2 Users, home directories, and history

```bash
cat /etc/passwd
ls -la /home
ls -la ~/.ssh/
history
```

Check only files readable by the current account. Relevant findings may include credentials in scripts, SSH material, application configuration, and command history.

### 12.3 Sudo permissions

```bash
sudo -l
```

Review every allowed command, target user, password requirement, environment setting, and argument restriction. A sudo entry is not automatically root access; determine whether that specific binary or configuration permits privilege boundary crossing.

### 12.4 SUID and SGID

```bash
find / -type f -perm -4000 2>/dev/null
find / -type f -perm -2000 2>/dev/null
```

Investigate unusual/custom binaries and compare expected ownership and permissions. Use trusted references such as GTFOBins for understanding legitimate binary behavior, but validate the exact local configuration.

### 12.5 Processes and services

```bash
ps aux
ps aux | grep root
ss -lntp
```

Look for privileged services using writable scripts, unsafe paths, vulnerable versions, or exposed interfaces. Determine the exact execution user and file ownership.

### 12.6 Cron and scheduled jobs

```bash
cat /etc/crontab
ls -la /etc/cron.d/
ls -la /etc/cron.daily/
ls -la /etc/cron.hourly/
crontab -l
```

A root-owned scheduled task matters only if an unprivileged user can influence the script, dependency, configuration, or execution path.

### 12.7 Writable files and directories

```bash
find / -path /proc -prune -o -type d -perm -o+w 2>/dev/null
find / -path /proc -prune -o -type f -perm -o+w 2>/dev/null
```

Prioritize files that are executed or trusted by privileged processes. Do not modify system files without understanding their impact.

### 12.8 Credentials and file systems

```bash
grep -RniE 'password|passwd|username|secret|token' /etc 2>/dev/null
ls -l /etc/passwd /etc/shadow
lsblk
findmnt
df -h
ip neigh
```

Review accessible configuration, backup, mount, and credential locations. A string matching `password` is only a lead; establish whether it is real and relevant.

### 12.9 Linux decision guide

| Finding | Next question |
|---|---|
| Sudo command | Can its documented behavior cross the privilege boundary? |
| SUID binary | Is it unusual, vulnerable, or capable of unsafe privileged actions? |
| Writable cron script | Is it executed by root, and can this user modify it? |
| Root service | Does it trust a writable config, script, library, or path? |
| Credentials | Are they valid for another in-scope service/account? |
| Kernel/package version | Is there a matching advisory and safe, applicable validation? |
| Container/group membership | Does the permission provide host-level control in this configuration? |
| Capabilities | Does a capability on a specific binary create a practical privilege path? |

### 12.10 Verify privilege level

After any escalation attempt:

```bash
whoami
id
```

Confirm the resulting identity. Do not report root access unless the evidence demonstrates it.

---

## 13. Windows privilege escalation

Start with manual enumeration and verify the results of automated tools.

### 13.1 Host and account context

```powershell
whoami
whoami /all
hostname
systeminfo
ipconfig /all
route print
```

### 13.2 Users and groups

```powershell
net user
net localgroup
net localgroup Administrators
whoami /groups
```

Determine current membership, privileges, group nesting, and whether the account has access to administrative interfaces.

### 13.3 Processes, services, and applications

```powershell
tasklist /v
Get-Service
Get-Process
Get-CimInstance Win32_Product
```

> `Win32_Product` can trigger consistency checks and is not always the best inventory method. Prefer registry-based or other read-only inventory methods where available.

Review service configuration, binary path, executable permissions, service account, and writable directories. A service running as SYSTEM is not itself exploitable; determine whether an unprivileged user can influence its execution.

### 13.4 Windows privileges

```powershell
whoami /priv
```

Your notes cover important privileges and groups, including:

- `SeImpersonatePrivilege`
- `SeAssignPrimaryTokenPrivilege`
- `SeDebugPrivilege`
- `SeTakeOwnershipPrivilege`
- Event Log Readers
- DnsAdmins
- Hyper-V Administrators
- Print Operators
- Server Operators

For each finding, determine whether the privilege is enabled, the OS/build is affected, and the specific abuse path is applicable. Do not assume every listed privilege is exploitable on every Windows version.

### 13.5 Search for credentials and scripts

Review permitted locations such as:

- PowerShell history.
- Application configuration and backup files.
- Deployment and maintenance scripts.
- Scheduled task actions.
- Service configuration.
- Unattended installation files.
- Accessible user directories.
- Shared folders.

Protect any collected credentials and record the original file/path as evidence.

### 13.6 Automated enumeration

Tools in your notes include WinPEAS, Seatbelt, and JAWS. Use them only when permitted. Automated output is a lead list, not proof. Verify every potentially exploitable permission manually.

### 13.7 Verify privilege level

```powershell
whoami
whoami /groups
whoami /priv
```

Confirm the actual identity and group/privilege context after testing.

---

## 14. Active Directory methodology

The core AD mental model:

**What identity do I have? What can it access? What can it control? What identity can it legitimately or improperly become through a verified permission path?**

### 14.1 Identify the domain and Domain Controller

Common AD ports:

| Port | Service | Why it matters |
|---:|---|---|
| 53 | DNS | Domain and host resolution |
| 88 | Kerberos | Authentication |
| 135 | RPC | Windows remote procedure calls |
| 139/445 | NetBIOS/SMB | Shares and Windows networking |
| 389 | LDAP | Directory queries |
| 464 | Kerberos password change | Password operations |
| 636 | LDAPS | LDAP over TLS |
| 3268/3269 | Global Catalog | Forest-wide directory lookups |
| 3389 | RDP | Remote desktop |
| 5985/5986 | WinRM | Remote management |

A host exposing several of these services may be a Domain Controller, but verify it with DNS, LDAP, SMB, and host information rather than assuming from ports alone.

### 14.2 Discover domain details

From a Windows domain-joined host:

```powershell
whoami /all
echo %USERDOMAIN%
echo %USERDNSDOMAIN%
nltest /dsgetdc:DOMAIN
```

On Linux, use the domain and DC details observed from the lab. Do not reuse example names from training notes.

### 14.3 Enumerate users, computers, groups, and shares

The AD notes cover tools such as NetExec/CrackMapExec, enum4linux, rpcclient, LDAP tools, Kerbrute, and PowerView.

General questions:

- What domain and DC are present?
- Which users and groups exist?
- Which computers and servers exist?
- Which shares are accessible?
- What password policy applies?
- What sessions and privileged groups can be observed?
- Which objects and permissions can the current account enumerate?

### 14.4 Review password policy before any online testing

Check the domain lockout threshold, duration, observation window, and minimum password requirements. Do not spray unless the exam rules explicitly permit it. A password policy is not permission to test unlimited credentials.

### 14.5 Credentialed enumeration

Once a valid domain credential is found and its use is permitted, enumerate what that identity can see:

- Users and groups.
- Computers and operating systems.
- SMB shares and access levels.
- Logged-on sessions where allowed.
- SPNs.
- Delegation settings.
- Group Policy and SYSVOL.
- Object ACLs and relationships.
- Security controls and restrictions.

### 14.6 BloodHound

BloodHound helps visualize relationships among users, groups, computers, sessions, ACLs, GPOs, and trusts.

Typical process:

```text
Collect permitted directory data
        ↓
Import collection into BloodHound
        ↓
Review shortest paths and privileged relationships
        ↓
Verify the exact edge and target object
        ↓
Choose a scope-approved validation path
        ↓
Record evidence
```

Your notes mention SharpHound and bloodhound-python. Follow the current exam tool availability and constraints.

### 14.7 AD attack-condition decision guide

Do not memorize a tool before understanding the condition.

| Evidence / condition | Technique to study |
|---|---|
| Valid usernames and policy permits testing | Carefully controlled password testing |
| LLMNR/NBT-NS is enabled and poisoning is explicitly in scope | Name-resolution poisoning assessment |
| User account has an SPN | Kerberoasting |
| Account has Kerberos pre-authentication disabled | AS-REP roasting |
| Interesting object ACL | ACL/permission-path analysis |
| Readable SYSVOL or scripts | Search for exposed secrets and unsafe configuration |
| GPP `cpassword` in legacy policy data | GPP credential exposure |
| Replication rights are assigned to an account | DCSync risk analysis |
| GPO modification rights | Determine linked OUs and affected systems |
| Child/parent domain trust is present | Analyze trust direction, SID filtering, and permitted trust boundaries |

### 14.8 Kerberoasting versus AS-REP roasting

| | Kerberoasting | AS-REP roasting |
|---|---|---|
| Condition | Account has an SPN | Pre-authentication is not required |
| Kerberos material | Service ticket (TGS) | AS-REP response |
| Typical goal | Assess service-account password strength | Assess password strength of affected accounts |
| Key distinction | SPN | Pre-authentication setting |

A successful ticket request does not itself establish that a weak password exists. Password recovery is a separate offline step and should use only permitted lab material.

### 14.9 ACLs and group relationships

Important permissions in the notes:

| Permission | General meaning |
|---|---|
| `ForceChangePassword` | Can reset/change a target account password, subject to the exact right and controls |
| `GenericWrite` | Can modify certain attributes of an object |
| `GenericAll` | Broad control over an object |
| `AddMember` | Can add members to a group |
| `WriteOwner` | Can change object ownership |
| `WriteDACL` | Can modify an object's permissions |

Always ask:

1. Which principal has the permission?
2. Which exact object is affected?
3. Is the permission inherited or direct?
4. What operation is actually possible?
5. What downstream groups, users, computers, or policies are affected?
6. Can the path be validated without unnecessary impact?

Your notes contain a training example chain involving `wley`, `damundsen`, Help Desk Level 1, Information Technology, and `adunn`. Treat these as lab-specific names, not universal exam identities.

### 14.10 SYSVOL, GPP, and credential discovery

Review accessible scripts, policy files, and deployment artifacts for accidental secrets. Legacy Group Policy Preferences may contain `cpassword` values. A discovered value must be treated as sensitive and correlated with the relevant account/context before testing.

### 14.11 DCSync and trusts

DCSync requires specific directory replication rights; it is not synonymous with being a Domain Admin. Analyze effective rights and the object/domain they apply to.

For domain trusts, identify:

- Parent and child domains.
- Trust direction and type.
- SID filtering and related protections.
- Which domain is actually controlled.
- Whether any cross-domain path is supported by evidence.

Do not assume control of a child domain automatically implies control of a parent domain.

### 14.12 Security controls and living off the land

Your notes cover Defender, AppLocker, LAPS, PowerShell restrictions, and built-in Windows utilities. Record defensive controls and their effect on the assessment. Use built-in tools when they provide sufficient, permitted visibility; do not attempt to evade controls outside the exam rules.

### 14.13 AD completion checks

- [ ] Domain and DC identified.
- [ ] Current identity and privileges recorded.
- [ ] Users, groups, computers, and shares enumerated where allowed.
- [ ] Password policy reviewed before online authentication testing.
- [ ] Credentials and their source documented securely.
- [ ] BloodHound relationships reviewed where available.
- [ ] SPNs and pre-authentication settings reviewed.
- [ ] ACLs and group nesting analyzed.
- [ ] SYSVOL/GPP and policy permissions reviewed.
- [ ] Trust relationships understood.
- [ ] Any claimed access path verified and evidenced.

---

## 15. Pivoting and internal network enumeration

Pivoting is relevant only if the exam explicitly authorizes access to the internal network through a compromised host.

### 15.1 Understand the network first

On the compromised host, record:

- Network interfaces and addresses.
- Routing table.
- Connected subnets.
- Listening services.
- Host firewall and egress constraints.
- Which internal ranges are explicitly in scope.

Linux:

```bash
ip -br a
ip route
ss -lntp
```

Windows:

```powershell
ipconfig /all
route print
netstat -ano
```

### 15.2 Verify the tunnel before scanning

For Chisel, SSH dynamic forwarding, sshuttle, Socat, or Meterpreter port forwarding, establish:

1. A route or proxy that points to the intended internal network.
2. A reachable listening port.
3. A live tunnel.
4. Correct proxy configuration.
5. A target address within scope.

Your notes include Chisel SOCKS5, SSH dynamic forwarding, reverse port forwarding, sshuttle, Socat, and Meterpreter forwarding. Select only the technique compatible with the exam setup.

### 15.3 Nmap through SOCKS/ProxyChains

Nmap host-discovery probes may not work properly through SOCKS. A TCP connect scan with host discovery disabled can be more suitable in some environments:

```bash
proxychains nmap -n -sT -Pn -p PORT_LIST TARGET_IP
```

A proxy scan can be slow or incomplete. “No hosts up” through a proxy does not prove the network is empty. Verify tunnel connectivity independently.

### 15.4 Troubleshoot listeners and tunnels

```bash
ss -lntp
ps aux | grep '[c]hisel'
```

If a bind error says the address is already in use, identify the listener first. Do not kill an unknown or system process.

---

## 16. File transfer

Choose the method that is actually reachable from the source and destination. Verify the result after transfer.

### 16.1 HTTP

Start a temporary server in the directory containing the authorized file:

```bash
python3 -m http.server 8000
```

Download from another Linux host:

```bash
wget http://SERVER_IP:8000/FILE
```

Or:

```bash
curl http://SERVER_IP:8000/FILE -o FILE
```

### 16.2 SCP / SFTP

Start SSH where permitted:

```bash
sudo systemctl enable --now ssh
```

Upload:

```bash
scp FILE user@SERVER_IP:/tmp/
```

Download:

```bash
scp user@SERVER_IP:/tmp/FILE .
```

### 16.3 Netcat

Receiver:

```bash
nc -lvnp 9001 > received.bin
```

Sender:

```bash
nc SERVER_IP 9001 < FILE
```

Use this only in the authorized lab network and ensure the correct receiving path.

### 16.4 SMB

List shares:

```bash
smbclient -L //SERVER_IP/ -N
```

Connect:

```bash
smbclient //SERVER_IP/SHARE -U USERNAME
```

### 16.5 Verify file integrity

Linux:

```bash
sha256sum FILE
```

Windows PowerShell:

```powershell
Get-FileHash .\FILE -Algorithm SHA256
```

Matching SHA-256 hashes confirm that the transferred bytes match.

For FTP transfers of binary files, use `binary` mode.

---

## 17. Metasploit and supporting tools

### 17.1 Metasploit workflow

```text
Identify service/version
       ↓
Search relevant module
       ↓
Read module details and references
       ↓
Review required options and target compatibility
       ↓
Check only if the module and target support it
       ↓
Run only when permitted
       ↓
Verify session and actual identity
       ↓
Document evidence
```

Useful console commands:

```text
msfconsole
search PRODUCT
info MODULE
show options
show targets
check
sessions
jobs
```

Do not run an exploit just because Metasploit has a matching module. Verify product version, configuration, target compatibility, and exam permission first.

### 17.2 MSFvenom concepts

Your notes cover payload operating system, architecture, shell type, transport, format, and staged versus stageless payloads.

| Concept | Meaning |
|---|---|
| Staged | A small initial stage retrieves additional payload content |
| Stageless | The full payload is included in one payload |
| Bind | Target listens for an incoming connection |
| Reverse | Target initiates a connection back |
| Format | Executable/script/container format required by target |

Payload generation is not proof that the target can execute the payload or that the network path permits the callback.

### 17.3 Supporting tools

| Tool | Main use |
|---|---|
| Nmap | Host/port/service enumeration |
| Netcat | Banner checks and basic TCP transfer/testing |
| curl | HTTP request inspection |
| FFUF / Gobuster | Web content and virtual-host discovery |
| Burp Suite | HTTP interception, Repeater, Intruder, response comparison |
| WhatWeb | Web technology fingerprinting |
| Searchsploit | Local public-exploit search |
| Nessus / OpenVAS | Vulnerability assessment |
| John the Ripper / Hashcat | Offline password auditing |
| Impacket | Windows/AD protocol tooling |
| BloodHound / SharpHound | AD relationship analysis |
| NetExec | Network and Windows/SMB enumeration |
| LinPEAS / WinPEAS | Privilege-escalation enumeration leads |
| Evil-WinRM | Authorized WinRM access |
| Chisel / sshuttle / ProxyChains | Pivoting and tunneling |

---

## 18. Troubleshooting

Use a consistent troubleshooting process: identify the exact error, verify the route/service, check credentials and permissions, then change one variable at a time.

### Problem: Nmap reports no host or no ports

**Symptoms**

- Host appears down.
- All ports show filtered.
- No output from a scan through a proxy.

**Possible causes**

- ICMP/host discovery is blocked.
- The wrong interface or route is used.
- The target IP is incorrect.
- A firewall filters traffic.
- ProxyChains does not support the probes being used.

**Checks**

```bash
ip route get TARGET_IP
ip -br a
nmap -Pn -sT -p PORT_LIST TARGET_IP
```

For SOCKS/ProxyChains, verify the tunnel and use a supported TCP-connect scan where appropriate.

### Problem: FTP listing hangs

**Symptoms**

- FTP login works, but `ls` or `dir` does not return.

**Possible causes**

- Active/passive data-channel mismatch.
- Firewall blocks the data connection.
- FTP is being used through a proxy that does not handle its data channel.

**Checks**

- Toggle passive mode in the client.
- Check the data-channel connectivity.
- Test from a network path that can reach the target's FTP data port.

### Problem: SSH private key is rejected

**Symptoms**

- `Permission denied (publickey)`
- `UNPROTECTED PRIVATE KEY FILE`
- `invalid format`

**Possible causes**

- Incorrect username.
- Key does not belong to the account.
- Key permissions are too broad.
- Key is encrypted or malformed.

**Checks**

```bash
chmod 600 id_rsa
ssh-keygen -y -f id_rsa
ssh -vvv -i id_rsa USERNAME@TARGET_IP
```

### Problem: MySQL TLS/SSL compatibility error

**Symptoms**

- Client reports that SSL is required but unsupported, or client/server TLS options conflict.

**Possible causes**

- Client and server TLS capabilities differ.
- The lab service uses legacy configuration.
- The installed client version uses different option syntax.

**Checks**

- Review `mysql --help` for supported TLS options.
- Confirm server configuration and exam notes.
- Use a compatibility option only if explicitly appropriate for the lab.

### Problem: MySQL access denied

**Symptoms**

- Login fails despite a connection to port 3306.

**Possible causes**

- Wrong username/password.
- Account is restricted to a different source host.
- Authentication plugin mismatch.
- Remote login is disabled.

**Checks**

- Verify credential source and account host restrictions.
- Confirm correct client syntax.
- Avoid repeated guessing that may trigger controls.

### Problem: Web request returns 401 or 403

**Symptoms**

- Request is rejected or redirected to login.

**Possible causes**

- Missing/expired session.
- Incorrect role or permissions.
- Missing CSRF token.
- Incorrect content type or request format.
- Endpoint intentionally restricts the action.

**Checks**

- Capture a fresh legitimate request.
- Compare cookies, authorization headers, CSRF tokens, and body fields.
- Verify that the tested operation is within scope.

### Problem: Shell is unstable or non-interactive

**Symptoms**

- Terminal input behaves incorrectly.
- Interactive programs do not work.
- Shell closes unexpectedly.

**Possible causes**

- No PTY allocated.
- Network interruption.
- Listener/callback address is wrong.
- Command interpreter is limited.

**Checks**

- Confirm network route and listener.
- Identify available interpreters.
- Use a permitted TTY stabilization method.
- Record the account context before continuing.

### Problem: A privilege-escalation lead does not work

**Symptoms**

- Sudo command is denied.
- SUID technique fails.
- Service or scheduled task does not produce elevated access.

**Possible causes**

- The permission is not sufficient.
- A required condition is absent.
- Version/configuration differs.
- The file is not actually writable or executed by a privileged process.
- Security controls prevent the expected behavior.

**Checks**

- Re-read the exact permission and file metadata.
- Identify execution user and context.
- Verify prerequisites against the installed version.
- Return to enumeration instead of forcing an unsupported path.

---

## 19. Evidence and final reporting

A clean report is part of the assessment. Capture evidence as you work rather than reconstructing it at the end.

### 19.1 Evidence for every finding

Record:

- Finding title.
- Affected host, port, URL, and parameter.
- Relevant service/product/version.
- Required access and prerequisites.
- Exact command or HTTP request.
- Relevant output and response.
- Screenshot filename/number.
- Reproduction steps.
- Verified impact.
- Severity and rationale, if the reporting template requires it.
- Root cause.
- Remediation.
- Retest result, if applicable.

### 19.2 Reproduction format

Use this structure:

1. **Identify the target:** State the in-scope host/application and service.
2. **Enumerate:** Include exact command and the relevant output.
3. **Identify the weakness:** Explain why the observed version/configuration/permission is vulnerable.
4. **Validate:** Include the precise request, payload, or command and the response.
5. **Demonstrate impact:** Show only the necessary proof.
6. **Recommend remediation:** State the fix that addresses the root cause.

### 19.3 Screenshot and output discipline

- Capture a screenshot immediately after a meaningful result.
- Use sequential names such as `01_initial_nmap.png`, `02_smb_share.png`, and `03_access_proof.png`.
- Keep raw scan and request/response files.
- Do not edit evidence to change its meaning.
- Redact passwords, session cookies, private keys, personal data, and unrelated secrets.
- Do not claim a flag, shell, user, or privilege level without evidence.

### 19.4 Example finding template

```markdown
## Finding: [Concise title]

- **Target:** [Host / URL / service]
- **Port:** [Port, if applicable]
- **Severity:** [As required by the exam template]
- **Prerequisites:** [Required account or network position]

### Description
[Explain the root cause in clear technical language.]

### Evidence
[Include exact command/request and relevant output.]

### Reproduction Steps
1. [Step one]
2. [Step two]
3. [Step three]

### Impact
[Describe only the impact demonstrated by evidence.]

### Remediation
[Provide a specific server-side fix.]

### References
[Vendor advisory / relevant documentation]
```

### 19.5 Final report review

- [ ] All required findings are included.
- [ ] Every finding has reproducible steps.
- [ ] Commands, parameters, and requests are accurate.
- [ ] Screenshots support the written claims.
- [ ] Findings are not duplicated.
- [ ] Severity matches the demonstrated impact.
- [ ] Sensitive values are handled safely.
- [ ] Remediation is practical and specific.
- [ ] The report follows the current exam template and submission instructions.

---

## 20. Time management and final checks

Use the time limit stated by the exam portal. Do not assume a fixed duration from an older exam format.

### Suggested prioritization

1. **Scope and setup:** establish VPN, workspace, evidence capture, and target list.
2. **Broad discovery:** identify hosts and all relevant open ports.
3. **High-value enumeration:** investigate services with the strongest evidence and attack surface.
4. **Access paths:** validate credentials, application weaknesses, and service misconfigurations.
5. **Post-access:** enumerate every foothold and prioritize realistic privilege paths.
6. **Internal network:** pivot only if allowed and supported by evidence.
7. **Reporting:** reserve enough time to write, verify, and submit the report.

### When stuck

- Re-read the service enumeration and the exact output.
- Check alternate ports and UDP only where relevant.
- Review the application source, scripts, configuration, and files already discovered.
- Correlate usernames, hostnames, passwords, and services.
- Revisit the network route and current account permissions.
- Change one variable at a time.
- Avoid repeatedly running the same failed command without a new hypothesis.

### Final exam completion

- [ ] Confirm every required objective/flag using the method specified by the exam.
- [ ] Verify the evidence file exists and is readable.
- [ ] Confirm screenshots and notes are saved.
- [ ] Check that all findings are in the required report.
- [ ] Submit through the official exam workflow.
- [ ] Confirm the portal shows successful submission/receipt, if provided.
- [ ] Do not leave sensitive credential files in public or shared locations.

---

## 21. Quick command reference

Replace every placeholder with an actual in-scope value.

### Network and service discovery

| Task | Command |
|---|---|
| Interface / routes | `ip -br a && ip route` |
| Route to target | `ip route get TARGET_IP` |
| Initial scan | `nmap -sC -sV TARGET_IP` |
| All TCP ports | `nmap -p- --open TARGET_IP` |
| Detailed ports | `nmap -sC -sV -p PORT_LIST TARGET_IP` |
| UDP top 100 | `sudo nmap -sU --top-ports 100 -sV TARGET_IP` |
| Save all formats | `nmap -oA scans/target TARGET_IP` |

### Service enumeration

| Task | Command |
|---|---|
| FTP | `ftp TARGET_IP` |
| SSH | `ssh USERNAME@TARGET_IP` |
| SSH key | `ssh -i id_rsa USERNAME@TARGET_IP` |
| DNS | `dig @TARGET_IP DOMAIN A` |
| DNS AXFR | `dig axfr DOMAIN @TARGET_IP` |
| SMB shares | `smbclient -L //TARGET_IP -N` |
| SMB permissions | `smbmap -H TARGET_IP` |
| NFS exports | `showmount -e TARGET_IP` |
| SNMP | `snmpwalk -v2c -c COMMUNITY TARGET_IP 1.3.6.1.2.1.1` |
| HTTP | `curl -i http://TARGET_IP/` |
| HTTPS | `curl -k -i https://TARGET_IP/` |
| MySQL | `mysql -h TARGET_IP -u USERNAME -p` |
| MSSQL version | `SELECT @@VERSION;` |
| RDP | `xfreerdp /v:TARGET_IP /u:USERNAME` |
| WinRM | `evil-winrm -i TARGET_IP -u USERNAME -p 'PASSWORD'` |

### Web testing

| Task | Command |
|---|---|
| Fingerprint | `whatweb http://TARGET/` |
| FFUF directories | `ffuf -u http://TARGET/FUZZ -w WORDLIST` |
| Capture headers | `curl -i http://TARGET/` |
| Follow redirects | `curl -i -L http://TARGET/` |
| SQLMap simple URL | `sqlmap -u 'http://TARGET/?id=1'` |
| SQLMap saved request | `sqlmap -r request.txt` |

### Post-access enumeration

| Task | Linux | Windows |
|---|---|---|
| Current user | `whoami` | `whoami` |
| Identity/privileges | `id` | `whoami /all` |
| Hostname | `hostname` | `hostname` |
| Network | `ip a; ip route` | `ipconfig /all; route print` |
| Processes | `ps aux` | `tasklist /v` |
| Listening ports | `ss -lntp` | `netstat -ano` |
| Privilege leads | `sudo -l` | `whoami /priv` |

### File transfer and verification

| Task | Command |
|---|---|
| HTTP server | `python3 -m http.server 8000` |
| HTTP download | `wget http://SERVER_IP:8000/FILE` |
| SCP upload | `scp FILE user@SERVER_IP:/tmp/` |
| SCP download | `scp user@SERVER_IP:/tmp/FILE .` |
| SHA-256 Linux | `sha256sum FILE` |
| SHA-256 Windows | `Get-FileHash .\\FILE -Algorithm SHA256` |

---

## 22. Exam-day checklist

### Before scanning

- [ ] Read current exam rules and scope.
- [ ] Confirm VPN/interface and routes.
- [ ] Create workspace and evidence folders.
- [ ] Start a private terminal log.
- [ ] Record assigned targets and constraints.

### Enumeration

- [ ] Host discovery performed where required.
- [ ] All relevant TCP ports checked.
- [ ] Relevant UDP services checked.
- [ ] Service versions and banners recorded.
- [ ] Each service enumerated individually.
- [ ] Web applications mapped and requests saved.
- [ ] Scanner findings reviewed and manually validated.

### Access and escalation

- [ ] Credentials traced to their source.
- [ ] Online authentication testing performed only if allowed.
- [ ] Initial access context recorded.
- [ ] Linux/Windows local enumeration completed.
- [ ] AD identity, ACLs, SPNs, shares, and policy reviewed where applicable.
- [ ] Pivoting used only within permitted scope.
- [ ] Final privilege/objective claims supported by evidence.

### Reporting and submission

- [ ] Exact commands and requests preserved.
- [ ] Screenshots numbered and referenced.
- [ ] Reproduction steps are complete.
- [ ] Impact is evidence-based.
- [ ] Remediation is specific.
- [ ] Secrets are redacted from shared report material.
- [ ] Report checked against the official template.
- [ ] Submission completed through the official portal.

---

## Source notes in this repository

This runbook consolidates and reorganizes the repository's study material, including:

- Getting Started and Nmap.
- File Transfer.
- Footprinting and Common Services.
- Web Information Gathering and Vulnerability Assessment.
- Shells and Payloads.
- Active Directory Enumeration and Attacks.
- Password Attacks.
- Pivoting, Tunneling, and Port Forwarding.
- Metasploit and Web Proxies.
- FFUF and Web Application Attacks.
- SQL Injection and SQLMap.
- XSS, File Inclusion, File Uploads, and Command Injection.
- Attacking Common Applications.
- Windows and Linux Privilege Escalation.

Use the detailed module notes for deeper explanations and lab-specific examples. This master file is the **ordered exam workflow and quick operational reference**; it does not replace the current exam instructions.

---

**Author:** Kartik Yadav  
**Repository:** [KartikkYadav/CPTS](https://github.com/KartikkYadav/CPTS)  
**Purpose:** CPTS preparation and authorized HTB/security-lab practice.
