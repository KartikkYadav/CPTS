# 🧠 Service Scanning & Enumeration – Summary

## 🌐 What is Service Scanning?
- Identify:
  - **Operating System**
  - **Running services**
  - **Open ports**
- Goal:
  - Find **misconfigurations / vulnerabilities**
  - Exploit unintended behavior  

---

## 🔢 Ports Basics
- Range: **1 – 65535**
- Well-known ports: **1 – 1023**
- Each service runs on a **specific port**

### 📌 Common Ports
- 21 → FTP  
- 22 → SSH  
- 80 → HTTP  
- 139/445 → SMB  

---

## 🔍 Nmap (Network Mapper)

### 🟢 Basic Scan
    nmap <target-ip>

- Scans **top 1000 ports**
- Shows:
  - Open ports
  - Service names  

---

### 🔥 Advanced Scan
    nmap -sC -sV -p- <target-ip>

- `-sC` → default scripts  
- `-sV` → service version detection  
- `-p-` → scan all ports  

### 🎯 Output Includes
- Service versions  
- OS detection hints  
- Misconfigurations  

---

## 🧠 Key Observations
- Port **22 (SSH)** → Linux/Unix indication  
- Port **3389 (RDP)** → Windows  
- Version info helps identify:
  - OS version  
  - Vulnerabilities  

---

## 🏷 Banner Grabbing

### 📌 Using Netcat
    nc -nv <target-ip> 21

### Example Output
    220 (vsFTPd 3.0.3)

- Helps identify **service & version quickly**

---

# 📁 FTP Enumeration

- Port: **21**

### 🔓 Anonymous Login
    ftp <target-ip>

Login:
    anonymous

### Useful Commands
    ls
    cd
    get <file>

### 🎯 Example Finding
- File: `login.txt`
- Credentials:
    admin:ftp@dmin123

---

# 🖥 SMB Enumeration

- Ports: **139, 445**
- Used for **file sharing**

---

## 🔍 SMB Scan with Nmap
    nmap --script smb-os-discovery.nse -p445 <target-ip>

### 🎯 Reveals
- OS version  
- Hostname  
- Workgroup  

---

## 📂 List SMB Shares
    smbclient -L //<target-ip> -N

### Example Output
    print$
    users
    IPC$

---

## 🔐 Access SMB Share

### Anonymous Access
    smbclient //<target-ip>/users -N

### ❌ If Error:
    NT_STATUS_ACCESS_DENIED

👉 Means credentials required

---

### ✅ Login with Credentials
    smbclient //<target-ip>/users -U bob

Example:
    bob:Welcome1

---

## 📁 SMB Navigation

### Commands
    ls
    cd <folder>
    get <file>

### 🎯 Example
- File: `passwords.txt`
    get passwords.txt

---

# 📡 SNMP Enumeration

- Uses **community strings**

### 🔑 Default Strings
    public
    private

---

## 🔍 SNMP Walk
    snmpwalk -v 2c -c public <target-ip>

### 🎯 Reveals
- System info  
- Running processes  
- Network details  

---

## 💣 SNMP Brute Force
    onesixtyone -c dict.txt <target-ip>

---

# 🔥 Key Takeaways

### 🧭 Standard Workflow
1. Scan with Nmap  
2. Enumerate services  
3. Identify misconfigurations  
4. Extract credentials  
5. Pivot to other services  

---

## ⚡ Real-World Insight
- Services often expose:
  - Credentials  
  - Sensitive files  
- **SMB & FTP are high-value targets in CTFs**

---

# 🏁 Summary
- Enumeration is **critical phase**
- Misconfigured services = **entry point**
- Always:
  - Think like attacker  
  - Chain vulnerabilities  
