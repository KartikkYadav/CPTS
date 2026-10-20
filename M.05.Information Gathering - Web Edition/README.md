# Information Gathering - Web Edition - HTB Academy Guide

A practical CPTS module focused on **web reconnaissance, WHOIS, DNS enumeration, subdomain discovery, zone transfers, virtual hosts, fingerprinting, crawling, and recon automation**.

The module progresses from passive web intelligence to active discovery and finishes with a skills-assessment lab combining multiple reconnaissance techniques.

---

## 📚 Module Overview

**Web Recon → WHOIS → DNS → Subdomains → AXFR → VHosts → Fingerprinting → Crawling → Recon Automation → Skills Assessment**

The notes emphasize building a complete picture of a web target before vulnerability assessment and exploitation.

---

## 📂 Contents

| # | Topic | Main Focus |
|---|---|---|
| 01 | [Introduction](./01.Intriduction.md) | Web reconnaissance, information-gathering phases, active vs passive recon |
| 02 | [WHOIS](./02.Whois.md) | Domain registration, ownership, registrar, infrastructure clues |
| 03 | [DNS](./03.DNS.md) | DNS records and reconnaissance tools |
| 04 | [Subdomain Bruteforcing Using DNS](./04.Subdomain%20Bruteforcing_Using_DNS_IMPORTANT.md) | Wordlists, recursive subdomain discovery, dnsenum and AXFR |
| 05 | [Zone Transfer Vulnerability (AXFR)](./05.Zone%20Transfer%20Vulnerability%20(AXFR).md) | DNS zone-transfer discovery and information disclosure |
| 06 | [VHost](./06.Vhost.md) | Virtual-host discovery and Host-header fuzzing |
| 07 | [Fingerprinting](./07.Fingerprinting.md) | HTTP headers, technologies, WAFs, Nikto and target profiling |
| 08 | [Web Crawling](./08.Web%20Crawling_IMPORTANT.md) | Spidering, ReconSpider, endpoints, files, JavaScript and comments |
| 09 | [Automating Recon](./09.Automating%20Recon.md) | FinalRecon, Recon-ng, theHarvester, SpiderFoot and OSINT automation |
| 10 | [Skills Assessment](./10.Skills%20Assessment_Final_Lab.md) | Integrated WHOIS, vhost, robots.txt and crawling assessment |

---

## 🌐 01. Web Reconnaissance

The introduction defines web reconnaissance as an initial information-gathering phase used to identify:

- Domains
- IP addresses
- Subdomains
- Hidden information
- Attack-surface elements

### Active vs Passive Recon

| Feature | Active | Passive |
|---|---|---|
| Interaction | Direct | Indirect / public sources |
| Detection Risk | Higher | Lower |
| Data Depth | Generally higher | Generally limited |

The notes place web reconnaissance within the broader penetration-testing flow from pre-engagement through post-engagement.

---

## 🧾 02. WHOIS

WHOIS is used to retrieve domain-registration information.

### Information of Interest

- Registrar
- Creation and expiration dates
- Registrant / organization details where available
- Name servers
- Domain status
- Infrastructure clues

Basic command:

```bash
whois example.com
```

The notes also discuss WHOIS limitations such as privacy protection and redacted registration data.

---

## 🌐 03. DNS Reconnaissance

The DNS notes focus on using multiple tools according to the depth of information required.

### Tools

**dig · nslookup · host · dnsenum · fierce · dnsrecon · theHarvester**

### Practical Roles

| Tool | Use |
|---|---|
| `dig` | Detailed manual DNS queries |
| `nslookup` | Quick DNS lookups |
| `host` | Fast concise queries |
| `dnsenum` | Automated enumeration and brute force |
| `fierce` | Subdomain discovery and wildcard detection |
| `dnsrecon` | Multiple DNS reconnaissance techniques |
| `theHarvester` | DNS/OSINT/email discovery |

---

## 🔎 04. Subdomain Bruteforcing

Subdomain brute-forcing uses a wordlist to generate candidate hostnames and resolve them through DNS.

### Workflow

**Choose Wordlist → Generate Candidate Subdomains → Query DNS → Validate Results**

The notes focus especially on `dnsenum`.

Example:

```bash
dnsenum --enum inlanefreight.com \
-f /usr/share/seclists/Discovery/DNS/subdomains-top1million-20000.txt \
-r
```

Key options:

- `--enum` → enable enumeration
- `-f` → wordlist
- `-r` → recursive brute-forcing

---

## 🔁 05. DNS Zone Transfer (AXFR)

An AXFR issue occurs when a DNS server permits an unauthorized party to retrieve an entire DNS zone.

### Basic Test

```bash
dig axfr @<nameserver> <domain>
```

### Recommended Workflow

```text
Find Nameservers
      ↓
Query NS / SOA
      ↓
Try AXFR Against Each NS
      ↓
Review Discovered Records
```

A successful transfer can expose hostnames, internal names, and IP mappings.

---

## 🏠 06. Virtual Host Enumeration

Virtual hosts can expose websites that do not have publicly resolvable DNS records.

The notes explain the distinction:

**Subdomain → DNS-based discovery**

**VHost → HTTP Host-header-based discovery**

### Tool

```bash
gobuster vhost -u http://<IP> \
-w /usr/share/seclists/Discovery/DNS/subdomains-top1million-110000.txt \
--append-domain
```

Other referenced tools include **ffuf** and **feroxbuster**.

---

## 🧬 07. Fingerprinting

Fingerprinting answers two important questions:

**What is running?**

**What should be assessed next?**

### Techniques

HTTP headers:

```bash
curl -I <target>
curl -I -L <target>
```

WAF detection:

```bash
wafw00f <target>
```

Additional discovery:

```bash
nikto -h <target> -Tuning b
```

The notes use multiple clues together to identify technologies such as WordPress and to understand how WAF controls may affect testing.

---

## 🕷️ 08. Web Crawling

Web crawling systematically extracts information from web applications.

The notes cover:

- Links
- Emails
- Files
- JavaScript
- Comments
- Form fields
- Internal and external URLs

### ReconSpider

The source notes use ReconSpider and produce:

`results.json`

Example:

```bash
python3 ReconSpider.py http://inlanefreight.com
```

The resulting JSON can be reviewed for:

- Emails
- Links
- External files
- JavaScript files
- Forms
- Images
- Comments

---

## 🤖 09. Automating Recon

Automation improves:

- Speed
- Scalability
- Consistency
- Coverage
- Integration between tools

### Tools Covered

**FinalRecon · Recon-ng · theHarvester · SpiderFoot · OSINT Framework**

Example:

```bash
theHarvester -d <domain> -b google
```

The notes emphasize that automation does not replace manual validation.

### Practical Flow

**theHarvester → Recon-ng / SpiderFoot → DNS Enumeration → Crawling → Manual Validation**

---

## 🧪 10. Skills Assessment

The final lab combines several techniques:

- WHOIS
- HTTP header analysis
- robots.txt review
- VHost discovery
- Subdomain enumeration
- Web crawling
- ReconSpider
- Host-file updates

The lab demonstrates an important CPTS principle: a finding may become useful only after it is correlated with another discovery.

Example chain:

```text
VHost Discovery
      ↓
robots.txt
      ↓
Hidden Directory
      ↓
API Key / Sensitive Information
```

Another path in the lab combines:

```text
Nested VHost Discovery
      ↓
ReconSpider
      ↓
Developer Artifacts
      ↓
Email / API Key Discovery
```

---

## 🧭 Practical Web Information-Gathering Workflow

```text
1. Define Scope
      ↓
2. Passive Recon
   WHOIS / DNS / Public Sources
      ↓
3. Subdomain Discovery
      ↓
4. VHost Discovery
      ↓
5. Fingerprinting
      ↓
6. Crawling
      ↓
7. Automate & Correlate
      ↓
8. Validate Findings
      ↓
9. Prepare for Vulnerability Assessment
```

---

## 🛠️ Tools Covered

**WHOIS · dig · nslookup · host · dnsenum · dnsrecon · fierce · amass · assetfinder · puredns · Gobuster · FFUF · Feroxbuster · curl · wafw00f · Nikto · Burp Suite · OWASP ZAP · Scrapy · ReconSpider · FinalRecon · Recon-ng · theHarvester · SpiderFoot**

---

## 🧠 Key Learning Points

### Start With Passive Information

Publicly available information can reduce unnecessary interaction with the target.

### Check DNS and VHosts Separately

A hostname may exist at the application layer even when it is not visible through normal DNS enumeration.

### Fingerprint Before Testing Deeply

Knowing the server, framework, CMS, and WAF can help prioritize later assessment.

### Crawl, Then Correlate

JavaScript files, comments, hidden paths, forms, and historical content may reveal useful connections.

### Validate Automated Results

Recon tools can produce false positives or incomplete results. Manual verification is essential.

---

## 📖 Recommended Study Order

**1. Introduction → 2. WHOIS → 3. DNS → 4. Subdomain Bruteforcing → 5. AXFR → 6. VHosts → 7. Fingerprinting → 8. Web Crawling → 9. Recon Automation → 10. Skills Assessment**

---

## ⚠️ Responsible Use

Use these techniques only for:

**HTB Academy · CPTS Preparation · CTFs · Security Labs · Authorized Penetration Testing**

Keep active enumeration, brute-forcing, crawling, and host-header testing within the defined scope.

---

## 👤 Author

**Kartik Yadav**

Cybersecurity | Penetration Testing | CPTS Preparation

GitHub: [KartikkYadav](https://github.com/KartikkYadav)

---

⭐ **A practical HTB Academy reference for web reconnaissance, DNS intelligence, virtual-host discovery, crawling, fingerprinting, and recon automation.**
