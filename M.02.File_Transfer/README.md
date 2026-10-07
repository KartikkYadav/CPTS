# M.02 — File Transfer

A practical CPTS module covering **file transfer between Linux and Windows systems** using common protocols, native operating-system tools, PowerShell, and lightweight file-transfer servers.

The notes focus on understanding how files can be transferred in both directions, how to choose an appropriate transfer method, and how to verify that transferred files remain intact.

---

## 📚 Module Overview

File transfer is an important skill during penetration-testing engagements and security labs. Different environments may provide different levels of access, available protocols, firewall restrictions, and installed tooling.

This module covers:

**FTP → HTTP → SCP/SFTP → SMB → Netcat → TFTP → PowerShell → WebDAV → Base64 Transfers → File Integrity Verification**

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [File Transfer — Final Notes](./01.File_Transfer_Final.md) | Linux/Windows transfer methods, common protocols, troubleshooting, and integrity verification |
| 02 | [Windows File Transfer Methods](./02.Windows%20File%20Transfer%20Methods.md) | PowerShell, Base64, SMB, FTP, WebDAV, upload/download workflows, and Windows-native techniques |

---

## 🔄 01. File Transfer — Final Notes

**[File Transfer — Final Notes](./01.File_Transfer_Final.md)**

Provides a cross-platform quick reference for transferring files between Linux and Windows.

### Covered Methods

| Method | Linux Server | Windows Client | Windows Server | Linux Client |
|---|---|---|---|---|
| FTP | pyftpdlib | ftp | IIS FTP | ftp / curl |
| HTTP | Python HTTP server | curl / PowerShell | IIS / Python | wget / curl |
| SCP/SFTP | sshd | scp / sftp | OpenSSH | scp / sftp |
| SMB | Samba | net use | SMB | smbclient |
| Netcat | nc | ncat | ncat | nc |
| TFTP | tftpd-hpa | tftp | TFTP Server | tftp |

### Linux Topics

- FTP server and client operation
- HTTP file serving
- SCP and SFTP
- Netcat transfers
- Samba and SMB shares
- TFTP
- Linux-to-Windows transfers
- Windows-to-Linux transfers
- File hashes and size verification
- Listening-port checks
- Firewall troubleshooting
- Binary transfer considerations

### File Integrity

The notes recommend verifying transferred files using SHA-256:

Linux:

```bash
sha256sum file.zip
```

Windows:

```powershell
Get-FileHash .\file.zip -Algorithm SHA256
```

Identical hashes provide confidence that the file was transferred without modification.

---

## 🪟 02. Windows File Transfer Methods

**[Windows File Transfer Methods](./02.Windows%20File%20Transfer%20Methods.md)**

Focuses on Windows-native and cross-platform file-transfer techniques.

### Main Areas

- PowerShell Base64 transfer
- HTTP/HTTPS downloads
- `Invoke-WebRequest`
- `Net.WebClient`
- SMB transfers
- FTP transfers
- WebDAV
- PowerShell-based uploads
- Base64 upload workflows
- Fileless download concepts
- Windows command-line FTP
- File integrity verification

---

## 🧬 Base64 Transfers

Base64 provides a way to represent binary file data as text.

The notes cover workflows such as:

```text
File
  ↓
Base64 Encode
  ↓
Transfer Text
  ↓
Base64 Decode
  ↓
Original File
```

### Linux → Windows

The Linux side can encode a file:

```bash
cat file.txt | base64 -w 0
```

The Windows side can decode the Base64 data back into a file.

### Windows → Linux

The notes also cover encoding file bytes in PowerShell, transferring the Base64 representation, decoding it on Linux, and comparing hashes.

### Important Limitation

Large Base64 strings can become impractical because command-line and shell interfaces have input-size limitations. For larger files, protocol-based transfer is generally more appropriate.

---

## 🌐 HTTP / HTTPS Transfers

HTTP is one of the simplest methods for moving files between systems.

### Linux HTTP Server

```bash
python3 -m http.server 8000
```

### Linux Client

```bash
wget http://<SERVER_IP>:8000/file.txt
```

or:

```bash
curl http://<SERVER_IP>:8000/file.txt -o file.txt
```

### Windows PowerShell

```powershell
Invoke-WebRequest http://<SERVER_IP>:8000/file.txt -OutFile file.txt
```

The module also covers `curl.exe` on Windows and Python-based HTTP serving.

---

## 📁 FTP Transfers

The notes cover both interactive and non-interactive FTP transfers.

### Linux Server Example

```bash
python3 -m pyftpdlib -p 2121
```

### Linux Client

```bash
ftp <SERVER_IP> 2121
```

Common commands include:

```text
binary
ls
get file.zip
put file.txt
bye
```

The Windows notes also cover PowerShell FTP operations and command files for non-interactive transfers.

### Binary Mode

For binary files such as ZIP archives, executables, DLLs, images, and PDFs, the notes emphasize using:

```text
binary
```

before transfer where applicable.

---

## 🔐 SCP / SFTP

SCP and SFTP use SSH for authenticated file transfer.

### SCP Upload

```bash
scp file.txt user@<SERVER_IP>:/tmp/
```

### SCP Download

```bash
scp user@<SERVER_IP>:/tmp/file.txt .
```

### Recursive Directory Transfer

```bash
scp -r directory/ user@<SERVER_IP>:/tmp/
```

### SFTP

```bash
sftp user@<SERVER_IP>
```

The notes cover common interactive SFTP operations such as `get`, `put`, `cd`, and `lcd`.

---

## 🗂️ SMB Transfers

SMB is commonly used for file sharing in Windows environments.

The module covers:

- Samba
- `smbclient`
- Windows `net use`
- Share mapping
- Copying files through mapped shares
- Impacket SMB server
- SMB authentication

### Linux SMB Server

The notes demonstrate configuring a Samba share and restarting the Samba service.

### Windows Share Mapping

```cmd
net use Z: \\<SERVER_IP>\share
```

Files can then be copied to the mapped drive.

---

## 📡 Netcat Transfers

Netcat can transfer files directly over a TCP connection.

### Receiver

```bash
nc -lvnp 9001 > received.txt
```

### Sender

```bash
nc <SERVER_IP> 9001 < file.txt
```

The same approach can be used for binary data, provided the connection and redirection are handled correctly.

---

## 📤 TFTP

The Linux notes also include TFTP using `tftpd-hpa` and the `tftp` client.

Typical operations include:

```text
get file.txt
put file.txt
quit
```

TFTP is lightweight but provides fewer security and management features than protocols such as SFTP.

---

## 🪟 PowerShell Transfer Methods

The Windows-focused notes cover several PowerShell-based methods.

### Net.WebClient

Examples include:

- `DownloadFile`
- `DownloadData`
- `DownloadString`
- `UploadFile`

### Invoke-WebRequest

Common usage:

```powershell
Invoke-WebRequest https://example.com/file.ps1 -OutFile file.ps1
```

The notes also discuss `-UseBasicParsing` for compatibility with certain older Windows environments.

---

## 🌐 WebDAV

WebDAV extends HTTP to support file-oriented operations.

The notes cover:

- Installing a WebDAV server
- Running WebDAV over HTTP
- Accessing WebDAV from Windows
- `DavWWWRoot`
- Copying files to a WebDAV location

Example Windows access:

```cmd
dir \\192.168.49.128\DavWWWRoot
```

The source notes demonstrate an anonymous read/write WebDAV configuration for lab use; such configurations should not be exposed to untrusted networks.

---

## 🧠 Fileless Transfer & In-Memory Concepts

The Windows notes distinguish file transfer from **fileless execution**.

A file may be downloaded as data and then handled in memory rather than being written to disk.

Conceptually:

```text
Remote Resource
      ↓
Download as String/Data
      ↓
Memory
      ↓
Processing / Execution
```

The source material uses PowerShell `DownloadString` and `IEX` as an example of this pattern.

In real environments, these behaviors may be monitored by security controls such as endpoint detection and response systems.

---

## 🧭 Choosing a Transfer Method

A practical decision process from the notes:

```text
Need file transfer?
       ↓
What protocols are available?
       ↓
 ┌───────────────┬───────────────┐
 HTTP            SMB             SSH
 ↓               ↓               ↓
wget/curl     smbclient       SCP/SFTP
PowerShell    net use
       ↓
Are restrictions preventing the preferred method?
       ↓
Consider another authorized transfer mechanism
       ↓
Verify file integrity
```

Selection should depend on **available services, authentication requirements, firewall policy, file size, and the engagement scope**.

---

## 🔍 File Integrity Verification

Always verify important transferred files.

### Linux

```bash
sha256sum file.zip
```

### Windows

```powershell
Get-FileHash .\file.zip -Algorithm SHA256
```

Additional checks in the notes include:

```bash
ls -lh file.zip
```

and:

```cmd
dir file.zip
```

For archives, the notes also use:

```bash
unzip -t file.zip
```

---

## 🛠️ Troubleshooting

The module includes practical troubleshooting for:

### Listening Ports

Linux:

```bash
sudo ss -lntup
```

Windows:

```powershell
Get-NetTCPConnection -State Listen
```

### Connectivity Testing

Windows:

```powershell
Test-NetConnection <LINUX_IP> -Port <PORT>
```

### Firewall Checks

Linux:

```bash
sudo ufw status
```

The notes also cover temporarily allowing a required lab port and removing the rule afterward.

### Corrupted Transfers

Compare:

- SHA-256/MD5 hashes
- File size
- Archive integrity

For FTP transfers involving binary files, verify that binary mode is enabled.

---

## 🧠 Key Learning Points

### Cross-Platform Knowledge

A penetration tester should understand transfer options on both **Linux and Windows**.

### Protocol Selection Matters

HTTP, SMB, SSH, FTP, TFTP, Netcat, and WebDAV have different requirements and capabilities.

### Native Tools Can Be Valuable

Windows provides several built-in mechanisms through PowerShell and command-line utilities, reducing dependency on third-party software.

### Integrity Must Be Verified

A successful transfer does not automatically mean the file is intact. Hash verification is an important final step.

---

## 📖 Recommended Study Order

Study the module in this order:

**1. File Transfer Fundamentals → 2. HTTP/FTP → 3. SCP/SFTP → 4. SMB → 5. Netcat/TFTP → 6. PowerShell → 7. WebDAV → 8. Upload Methods → 9. Integrity Verification → 10. Troubleshooting**

---

## ⚠️ Responsible Use

These techniques are intended for:

**CPTS Preparation · Hack The Box · CTFs · Security Labs · Authorized Penetration Testing**

Only transfer files between systems where you have explicit authorization.

When working with real environments, protect credentials, avoid unnecessary exposure of temporary file servers, remove temporary firewall rules and shares, and clean up test infrastructure after an assessment.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical CPTS reference for Linux/Windows file transfers, PowerShell techniques, common protocols, integrity verification, and troubleshooting.**
