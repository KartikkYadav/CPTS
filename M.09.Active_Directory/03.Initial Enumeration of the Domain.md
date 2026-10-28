# Active Directory Enumeration & Attacks — Initial Enumeration of the Domain

**Module:** HTB Academy – Active Directory Enumeration & Attacks

---

## Setting Up — Common Internal Test Access Models

Ways a client may provide internal pentest access:

- Pentest Linux VM in their infra, calling back to a jump host over VPN (SSH access).
- Physical device plugged into an Ethernet port, calling back over VPN (SSH).
- Physical presence on-site, laptop plugged into Ethernet.
- Linux VM in Azure/AWS with internal network access, SSH via public key + whitelisted IP.
- VPN access directly into the internal network (limits some attacks, e.g. LLMNR/NBT-NS poisoning).
- Corporate laptop on the client's VPN.
- Managed Windows workstation on-site — may have limited/no internet, or full internet + local admin + AV in monitor mode.
- VDI (Citrix etc.) with similar configs as the managed workstation, accessed remotely.

### Scoping Dimensions
- **Grey box**: given in-scope IPs/CIDR ranges only.
- **Black box**: blind discovery from scratch.
- **Evasive / Non-evasive / Hybrid evasive** (start quiet, escalate loudness until detected, then go loud).
- May start **unauthenticated** or as a **standard domain user**.

### Inlanefreight's Chosen Approach (Example)
- Custom pentest VM inside their network, calling back to a jump host (SSH access).
- A Windows host also provided for loading tools.
- Start **unauthenticated**, but also given a standard domain user account (`htb-student`) for the Windows attack host.
- **Grey box**: given network range `172.16.5.0/23`, nothing else.
- **Non-evasive** testing.
- No credentials or network map provided for the "blind" phase.

---

## Tasks for This Phase

1. Enumerate the internal network — hosts, critical services, potential foothold avenues.
2. Use both active and passive measures to identify users, hosts, vulnerabilities.
3. **Document everything** — critical for later use.

Starting blind (no creds) is common — tests what an attacker could achieve via phishing, physical access, rogue wireless, or a rogue employee before the client decides whether to grant further access to speed up testing.

---

## Key Data Points to Track

| Data Point | Description |
|---|---|
| AD Users | Valid accounts to target for password spraying |
| AD Joined Computers | DCs, file servers, SQL servers, web servers, Exchange, DBs |
| Key Services | Kerberos, NetBIOS, LDAP, DNS |
| Vulnerable Hosts/Services | Anything offering a quick win / easy foothold |

---

## Methodology Overview

1. **Passive identification** of hosts on the network.
2. **Active validation** — services, names, potential vulns per host.
3. **Probe hosts** for interesting data.
4. **Regroup** — assess what's known; hopefully have a credential/account to pursue, or can begin credentialed enumeration.

---

## Identifying Hosts — Passive Methods

### Wireshark / tcpdump — Listening to the Wire

```bash
sudo wireshark
# or headless:
sudo tcpdump -i ens224
```

- On a switched network, visibility is limited to the current **broadcast domain**.
- **ARP requests/replies** reveal live hosts (e.g., `172.16.5.5, .25, .50, .100, .125`).
- **MDNS queries** can reveal hostnames (e.g., `ACADEMY-EA-WEB01.local`).
- Other tools: **net-creds**, **NetMiner**; Windows has built-in **pktmon.exe** (Win10+).
- **Always save PCAPs** — useful for later review and for report evidence.

### Responder — Passive Analyze Mode

```bash
sudo responder -I ens224 -A
```

- Listens/analyzes **LLMNR, NBT-NS, MDNS** traffic — in `-A` (Analyze) mode, **purely passive**, no poisoned packets sent.
- Surfaces additional hosts not seen via Wireshark alone — builds out the target IP/hostname list.

---

## Identifying Hosts — Active Methods

### ICMP Sweep — fping

```bash
fping -asgq 172.16.5.0/23
```

- `-a` show alive targets, `-s` print stats, `-g` generate target list from CIDR, `-q` suppress per-target output.
- Works **round-robin** across multiple hosts (vs. sequential ping) — faster bulk sweeps.
- ICMP isn't exhaustive — some hosts/firewalls block it — but gives a fast first pass.

### Nmap — Detailed Service Enumeration

```bash
sudo nmap -v -A -iL hosts.txt -oN /home/htb-student/Documents/host-enum
```

- `-A` = aggressive scan (OS detection, version detection, script scanning, traceroute).
- Feed in the IP list gathered from passive/fping results.
- **Best practice:** use `-oA` to save output in **all formats** (normal, XML, grepable) for logging and tool chaining.
- Focus on AD-related ports/services: **DNS (53), Kerberos (88), LDAP (389/636/3268/3269), SMB (445), RDP (3389)**.

### Example Finding — Domain Controller

```
Nmap scan report for inlanefreight.local (172.16.5.5)
53/tcp   open  domain
88/tcp   open  kerberos-sec
135/tcp  open  msrpc
139/tcp  open  netbios-ssn
389/tcp  open  ldap           (Domain: INLANEFREIGHT.LOCAL, Site: Default-First-Site-Name)
445/tcp  open  microsoft-ds?
464/tcp  open  kpasswd5?
636/tcp  open  ssl/ldap
3268/tcp open  ldap
3269/tcp open  ssl/ldap
3389/tcp open  ms-wbt-server
  rdp-ntlm-info:
    NetBIOS_Computer_Name: ACADEMY-EA-DC01
    DNS_Computer_Name: ACADEMY-EA-DC01.INLANEFREIGHT.LOCAL
```

→ Identifies `ACADEMY-EA-DC01.INLANEFREIGHT.LOCAL` as the **primary Domain Controller**.

### Example Finding — Legacy/Outdated Host

```
Nmap scan report for 172.16.5.100
80/tcp    open  http          Microsoft IIS httpd 7.5
445/tcp   open  microsoft-ds  Windows Server 2008 R2 Standard
1433/tcp  open  ms-sql-s      Microsoft SQL Server 2008 R2 10.50.1600.00
  smb2-security-mode: Message signing enabled but not required
  smb-os-discovery: OS: Windows Server 2008 R2 Standard
  NetBIOS computer name: ACADEMY-EA-CTX1
```

- **Legacy OS detected** (Server 2008 R2) → potential for old exploits: **EternalBlue, MS08-067**, etc. → possible SYSTEM shell.
- Legacy hosts persist in enterprise environments (old production/HVAC/equipment dependencies) — often shielded by extra perimeter defenses.
- **Caution:** get **written client approval** before exploiting legacy/fragile systems — risk of instability/outage. Client may prefer observe-and-report only.

### Scanning Caution

- Some Nmap NSE scripts run **active vulnerability checks** that can destabilize fragile systems (e.g., industrial sensors/logic controllers).
- **Understand what a scan does before running it** in a client environment.

---

## Identifying Users — Kerbrute

- Need a valid domain user (creds, NTLM hash, SYSTEM shell, or a shell as a domain user) to unlock deeper AD enumeration/attacks.
- **Kerbrute**: stealthier enumeration option — abuses the fact that **Kerberos pre-authentication failures typically don't trigger logs/alerts**.
- Commonly paired with **jsmith.txt / jsmith2.txt** wordlists from Insidetrust's statistically-likely-usernames repo.

### Build/Install

```bash
sudo git clone https://github.com/ropnop/kerbrute.git
cd kerbrute
make help          # list build targets: windows / linux / mac / all
sudo make all       # builds all platform/arch binaries into dist/
ls dist/
# kerbrute_darwin_amd64  kerbrute_linux_386  kerbrute_linux_amd64  kerbrute_windows_386.exe  kerbrute_windows_amd64.exe
```

- Compiling yourself (vs. using precompiled binaries) is **best practice** for tools introduced into a client environment.

### Add to PATH

```bash
sudo mv kerbrute_linux_amd64 /usr/local/bin/kerbrute
```

### Enumerate Users

```bash
kerbrute userenum -d INLANEFREIGHT.LOCAL --dc 172.16.5.5 jsmith.txt -o valid_ad_users
```

```
[+] VALID USERNAME: jjones@INLANEFREIGHT.LOCAL
[+] VALID USERNAME: sbrown@INLANEFREIGHT.LOCAL
...
Done! Tested 48705 usernames (56 valid) in 9.940 seconds
```

- **Warning:** a failed Kerberos pre-auth attempt still counts as a failed login and **can lock out accounts** — be mindful of lockout policies.
- Output feeds directly into later **password spraying** attacks.

---

## Identifying Potential Vulnerabilities — SYSTEM Access

- `NT AUTHORITY\SYSTEM` — built-in Windows account, **highest local privilege level**, runs most Windows services (and often third-party ones too).
- On a **domain-joined host**, SYSTEM can enumerate AD by **impersonating the computer account** (itself a type of AD account) — nearly equivalent to having a domain user account.

### Ways to Gain SYSTEM

- Remote exploits: **MS08-067, EternalBlue, BlueKeep**.
- Abusing a SYSTEM-context service, or **SeImpersonate** privilege abuse via **Juicy Potato** (works on older Windows; not always on Server 2019).
- Local privilege escalation flaws (e.g., Windows 10 Task Scheduler 0-day).
- Local admin + **PsExec** → spawn a SYSTEM cmd shell.

### What SYSTEM-Level Access Enables

- Domain enumeration via **BloodHound**, **PowerView**, or built-in tools.
- **Kerberoasting** / **ASREPRoasting** attacks.
- Running **Inveigh** to capture Net-NTLMv2 hashes or perform SMB relay.
- **Token impersonation** to hijack a privileged domain user.
- **ACL attacks**.

---

## A Word of Caution — Tool Choice vs. Test Style

- **Non-evasive** test, customer staff aware → noise doesn't matter much.
- **Evasive / adversarial assessment / red team** → stealth matters — Nmap sweeps and common pentest tools **will trigger alerts** for a prepared SOC/Blue Team.
- **Always clarify assessment goals and style with the client in writing** before starting.

---

## Key Takeaways

- Start **passive** (Wireshark/tcpdump, Responder analyze mode) → move to **active** (fping sweep → targeted Nmap `-A`) → **probe** discovered hosts.
- Save everything: PCAPs, Nmap output (`-oA`), notes — this is the backbone of both the assessment and the final report.
- **Kerbrute** is a low-noise way to validate domain usernames via Kerberos pre-auth — but can still trigger account lockouts.
- Legacy/outdated hosts found via Nmap are high-value targets but require **client sign-off** before active exploitation.
- The overarching goal of this phase: **obtain any domain user context (creds, hash, or SYSTEM shell)** to unlock the next phase of AD enumeration and attacks.

---

## Lab Notes (from this section's questions)

- **Q1:** commonName of host `172.16.5.5` → `ACADEMY-EA-DC01.INLANEFREIGHT.LOCAL`
- **Q2:** Host running Microsoft SQL Server 2019 15.00.2000.00 → `172.16.5.130` (identify via targeted Nmap scan of the subnet, looking for `ms-sql-s` service banner)
