# Linux Privilege Escalation - HTB Academy Guide

A practical CPTS module covering **Linux privilege-escalation fundamentals, manual enumeration, sudo, users and groups, filesystems, credentials, processes, cron jobs, services, binaries, system calls, and security defenses**.

---

## 📚 Module Overview

The module follows a post-compromise Linux workflow:

**Initial Shell → System Enumeration → User/Group Enumeration → Network & Filesystem Enumeration → Processes/Services → Sudo & Permissions → Credential Hunting → Identify Escalation Path**

A recurring theme is to understand manual enumeration commands before relying on automated tools.

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [Linux Privilege Escalation](./01.%20Linux%20Privilege%20Escalation.md) | OS/kernel enumeration, users, SSH keys, history, sudo, packages, and services |
| 02 | [Environment Enumeration](./02.Linux%20Privilege%20Escalation.md) | System, network, users, filesystems, security defenses, and automated enumeration |
| 03 | [Services & Internals Enumeration](./03.Linux%20Privilege%20Escalation%20.md) | Processes, cron, /proc, binaries, system calls, configuration files, and services |

---

## 🐧 01. Linux Privilege Escalation

**[Linux Privilege Escalation](./01.%20Linux%20Privilege%20Escalation.md)** introduces privilege escalation as the process of moving from a low-privileged account toward `root` or another privileged context.

### Initial Enumeration

Important areas include:

- OS distribution and version
- Kernel version
- Running services
- Running processes
- Installed packages
- Users and groups
- Home directories
- SSH keys
- Bash history
- Sudo permissions

### Core Commands

```bash
cat /etc/os-release
uname -a
uname -r
ps aux
dpkg -l
rpm -qa
cat /etc/passwd
ls -la /home
sudo -l
```

### Credentials & SSH Keys

The notes emphasize checking user home directories for:

- SSH private keys
- Configuration files
- History files
- Scripts
- Backup files

Useful examples:

```bash
ls -la ~/.ssh/
cat ~/.bash_history
```

### Sudo

Check:

```bash
sudo -l
```

The notes explain how sudo rules can reveal commands that a user can execute with elevated privileges and why `NOPASSWD` entries deserve particular attention.

---

## 🌐 02. Environment Enumeration

**[Environment Enumeration](./02.Linux%20Privilege%20Escalation.md)** provides a broader manual checklist.

### Identity

```bash
whoami
id
hostname
sudo -l
```

### OS & Kernel

```bash
cat /etc/os-release
uname -a
cat /proc/version
lscpu
```

### PATH & Environment

```bash
echo $PATH
env
```

The notes highlight writable PATH directories and exposed environment values as areas worth reviewing.

### Security Controls

The material references:

- AppArmor
- SELinux
- iptables / UFW
- Fail2ban
- Snort
- Exec Shield

### Filesystems

```bash
lsblk
df -h
cat /etc/fstab
mount
```

Look for:

- Accessible backups
- Interesting mounts
- Sensitive files
- Unusual filesystem configuration

### Network

```bash
ip a
ip route
route
netstat -rn
cat /etc/resolv.conf
arp -a
```

The goal is to identify interfaces, internal subnets, DNS, routes, and other systems that may matter for lateral movement.

### Users and Groups

```bash
cat /etc/passwd
cat /etc/group
getent group sudo
grep "sh$" /etc/passwd
ls -la /home
```

### Hidden & Temporary Files

The notes also cover:

- Hidden files/directories
- `/tmp`
- `/var/tmp`
- `/dev/shm`
- Configuration files
- Application secrets

Automated tools referenced include **LinPEAS** and **LinEnum**.

---

## ⚙️ 03. Services & Internals Enumeration

**[Services & Internals Enumeration](./03.Linux%20Privilege%20Escalation%20.md)** focuses on processes, scheduled tasks, binaries, and configuration.

### Network & Hosts

```bash
ip a
cat /etc/hosts
cat /etc/resolv.conf
ip route
```

### User Activity

```bash
lastlog
w
who
cat /etc/passwd
```

### Command History

```bash
history
cat ~/.bash_history
find / -type f \( -name '*_hist' -o -name '*_history' \) 2>/dev/null
```

### Cron Jobs

```bash
ls -la /etc/cron*
cat /etc/crontab
```

Review:

- Scheduled scripts
- Script permissions
- Writable cron files
- Relative paths

### Processes & /proc

```bash
ps aux
ps aux | grep root
find /proc -name cmdline -exec cat {} \; 2>/dev/null
```

Look for root-owned processes, scripts, unusual binaries, and useful command-line arguments.

### Installed Packages & Sudo

```bash
apt list --installed
sudo -V
```

### Binaries

```bash
ls -la /bin /usr/bin /usr/sbin
```

The notes recommend reviewing available binaries against **GTFOBins** where relevant to authorized privilege-escalation testing.

### System Call Analysis

```bash
strace ping -c 1 127.0.0.1
```

This introduces observing how programs interact with files, sockets, and system resources.

### Configuration Files & Scripts

```bash
find / -type f \( -name "*.conf" -o -name "*.config" \) 2>/dev/null
find / -type f -name "*.sh" 2>/dev/null
```

Review for:

- Credentials
- Sensitive paths
- Weak permissions
- Administrative scripts
- Misconfiguration

---

## 🔑 Common Linux Escalation Areas

The notes repeatedly identify these areas for investigation:

| Area | What to Review |
|---|---|
| Sudo | Commands executable with elevated rights |
| SUID / SGID | Privileged binaries and permissions |
| Cron | Writable scheduled scripts and jobs |
| Services | Root-owned vulnerable or misconfigured services |
| Credentials | History, configuration files, keys, backups |
| Packages | Outdated software and known local vulnerabilities |
| Filesystems | Writable mounts, backups, sensitive files |
| Processes | Root processes and command-line arguments |
| PATH | Writable executable directories |
| Binaries | GTFOBins-relevant functionality |
| Permissions | User/group/file ownership and write access |

---

## 🧭 Linux Privilege Escalation Workflow

```text
Initial Access
     ↓
Identify Current User
     ↓
OS / Kernel Enumeration
     ↓
Users / Groups / Sudo
     ↓
Network / Filesystem Enumeration
     ↓
Processes / Services / Cron
     ↓
Credentials / SSH Keys / History
     ↓
Permissions / SUID / Binaries
     ↓
Identify Candidate Escalation
     ↓
Validate Safely
     ↓
Root / Higher Privilege
```

---

## 🧠 Key Learning Points

### Enumeration Comes First

A complete host picture often reveals an escalation path without requiring immediate exploitation.

### Manual Skills Matter

LinPEAS and LinEnum are useful, but the notes emphasize understanding the underlying manual checks.

### Sudo Is High Value

Always inspect:

```bash
sudo -l
```

and understand the exact command permissions before testing an escalation path.

### History and Configuration Files Can Be Critical

Credentials, SSH destinations, scripts, and operational details may be exposed in readable files.

### Root-Owned Processes Deserve Attention

A privileged process using outdated software or insecure configuration can become an important escalation candidate.

---

## 🛠️ Tools & Commands Covered

**LinPEAS · LinEnum · GTFOBins · strace · sudo · ps · find · ip · route · netstat · dpkg · rpm**

---

## 📖 Recommended Study Order

**1. Linux Privilege Escalation Fundamentals → 2. Environment Enumeration → 3. Services/Internals → 4. Sudo & Permissions → 5. Credentials → 6. Processes/Cron → 7. Binaries & Exploit Validation**

---

## ⚠️ Responsible Use

Use these techniques only for:

**HTB Academy · CPTS Preparation · CTFs · Security Labs · Authorized Linux Assessments**

Privilege escalation and credential-discovery techniques can expose or change sensitive system resources. Keep testing inside the defined scope.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Linux Security | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for Linux privilege escalation, manual enumeration, sudo, credentials, processes, cron, and system internals.**
