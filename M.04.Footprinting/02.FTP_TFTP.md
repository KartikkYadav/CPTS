# 📁 FTP / TFTP 

## 🔹 FTP Basics
- Application layer protocol (like HTTP)
- Uses:
  - TCP 21 → Control channel
  - TCP 20 → Data channel
- Supports:
  - Authentication (username/password)
  - File upload/download
- ⚠️ Clear-text protocol → sniffable

---

## 🔹 Active vs Passive FTP

| Mode | Description |
|------|------------|
| Active | Server connects back to client (blocked by firewall) |
| Passive | Client connects to server (firewall-friendly) |

---

## 🔹 TFTP Basics
- Uses UDP (no reliability)
- No authentication ❌
- Limited to read/write permissions
- Used in internal networks only

---

## 🔹 Important FTP Misconfigurations

- Anonymous login enabled
- Weak credentials
- Writable directories
- Misconfigured permissions
- Exposed sensitive files

---

## 🔹 Anonymous Login

    ftp <IP>
    Name: anonymous

- Can:
  - List files
  - Download files
  - Sometimes upload files

---

## 🔹 Useful FTP Commands

    ls
    get file.txt
    put file.txt
    status
    debug
    trace

---

## 🔹 Download All Files

    wget -m --no-passive ftp://anonymous:anonymous@<IP>

---

## 🔹 Upload File (Important for RCE)

    put shell.php

👉 If FTP is linked to web server → possible **web shell → RCE**

---

## 🔹 vsFTPd Important Config

| Setting | Impact |
|--------|--------|
| anonymous_enable=YES | Anonymous access |
| write_enable=YES | Upload allowed |
| anon_upload_enable=YES | Anonymous upload |
| chroot_local_user=YES | Restrict users |
| hide_ids=YES | Hide usernames |

---

## 🔹 Enumeration (Nmap)

    nmap -sV -p21 -sC -A <IP>

### Important NSE Scripts:
- ftp-anon → check anonymous login
- ftp-syst → server info
- ftp-brute → brute force

---

## 🔹 Banner Grabbing

    nc <IP> 21
    telnet <IP> 21

---

## 🔹 FTP over SSL/TLS

    openssl s_client -connect <IP>:21 -starttls ftp

👉 Extract:
- Certificate
- Hostname
- Email

---

## 🔹 Attack Vectors

- Anonymous access
- Credential brute force
- Writable FTP → Web shell
- LFI → execute uploaded file
- Log poisoning → RCE

---

## 🔹 Key Pentesting Insight

👉 FTP is often:
- Misconfigured
- Overlooked internally
- Linked to web servers

➡️ Always check:
- Read access
- Write access
- File execution path

---

## 📌 Source
Extracted from your notes  
:contentReference[oaicite:0]{index=0}
