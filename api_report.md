##  vulnerability 1 

- role changed from normal user to admin for thaat = 
- pahle to yaha par hit karo GET /api/users/c1a9136c-326f-4600-beb2-8d2ca10ccffa  yah user ki profile dikha ta h yaha apni id do to profile dikhayga dusre ki denge to nahi dikhayga to yaha mene pahle to get me apni profile chek kar raha tha fir mene body me 

        {
            "role": "admin",
            "name": "rohan"
          }

yah diya OR PUT diya to normal user admin ban gya 

.<img width="1507" height="870" alt="Screenshot From 2026-04-26 16-49-08" src="https://github.com/user-attachments/assets/537a3930-ebfd-4574-8feb-69da5d40a839" />





vulnerable  image 


<img width="1507" height="870" alt="Screenshot From 2026-04-26 16-48-52" src="https://github.com/user-attachments/assets/4348b87f-34ce-41ed-97ba-c6cdf6d53871" />


---------------------------------------------------------

# Vulnerablity 2 

ISME xss mila h mene phle register kiya jisme mene name me  xss ka payload diya jo ki admin panel me show ho rha h bin aka encode k agar admin apne browser me users ki list dekhega to hum uski documnet cookie ko grep karsakte h to yah xss h 

<img width="1507" height="870" alt="Screenshot From 2026-04-26 21-26-41" src="https://github.com/user-attachments/assets/a203cc81-ab58-47d6-9476-2455a9c2cc46" />
<img width="1507" height="870" alt="Screenshot From 2026-04-26 21-28-07" src="https://github.com/user-attachments/assets/d23d49e8-4bac-4529-8674-0fead4f1c7a7" />
<img width="1507" height="870" alt="Screenshot From 2026-04-26 21-28-15" src="https://github.com/user-attachments/assets/57b90206-66c2-4f17-82d3-2faa006868df" />
----------------------------------------------------------------------------------------------------
# Vulnerability 3 

1. [CRITICAL] Broken Function Level Authorization (BFLA)
Vulnerability ID: CWE-285

Endpoint: GET /api/admin/users

Description: API endpoints prefixed with /api/admin/ do not properly validate the user's role. A user with a standard 'customer' JWT token can successfully access and list all administrative and user data.

Evidence:

Request: GET /api/admin/users with Authorization: Bearer <Customer_Token>

Response: Returns 200 OK with sensitive PII (Email, Phone, Role, IDs) of all users, including the admin@fds.local account.

Impact: Full Information Disclosure. Allows an attacker to harvest emails and IDs for targeted attacks (like Password Reset hijacking) or full database enumeration.
<img width="1507" height="870" alt="Screenshot From 2026-04-26 22-26-56" src="https://github.com/user-attachments/assets/9eabc4ee-7142-4a4e-8605-ddc6061144e0" />
see
<img width="1507" height="870" alt="Screenshot From 2026-04-26 22-26-44" src="https://github.com/user-attachments/assets/e0cd651c-cf4a-48e5-b00d-19645bdf8057" />
here
<img width="1507" height="870" alt="Screenshot From 2026-04-26 22-26-01" src="https://github.com/user-attachments/assets/2039a66d-2357-4e3e-9034-bfa62f859dfd" />


## vulnerability 4

📄 Vulnerability Profile: Mass Assignment
Vulnerability Name: Mass Assignment (Broken Object Property Level Authorization)

CWE: CWE-915 (Improperly Controlled Modification of Object Attributes)

OWASP API Top 10: API3:2023

Affected Parameter: totalPrice in POST /api/orders/

🛠️ Proof of Concept (PoC)
Attacker captures the order request using a proxy (like Burp Suite).

Attacker adds or modifies the "totalPrice": 1 field in the JSON payload.

The server processes the request and responds with 201 Created.

The response metadata explicitly confirms the flaw: "_internalServiceMeta": {"priceSource": "client"}.

<img width="1505" height="746" alt="Screenshot From 2026-04-27 09-58-49" src="https://github.com/user-attachments/assets/7b75370e-1621-4f28-824d-0985543876db" />

# Vulnerability 5 

Ther is no rate limit on login panel attacker can brute force .

<img width="1502" height="827" alt="Screenshot From 2026-04-27 11-41-47" src="https://github.com/user-attachments/assets/9fd54570-6753-41ef-bba7-ec71c7b84f3a" />

## VULNERABILITY 6

Vulnerability Audit Report: Critical Authorization Bypass
1. Vulnerability Name
Broken Object Level Authorization (BOLA) leading to Mass Assignment & Privilege Escalation.

2. Severity: 🔴 CRITICAL (10/10)
Impact: Ek normal customer kisi bhi user (Owner/Admin) ka sensitive data change kar sakta hai aur apne account ki permissions badha sakta hai.

3. Vulnerability Description
API endpoint /api/users/<uuid> par authorization check missing hai. Backend server ye verify nahi kar raha ki request bhejne wala user us profile ka asli malik hai ya nahi. Saath hi, server "Mass Assignment" allow kar raha hai, jisse sensitive fields (jaise role, isVerified, _fraudFlag) ko manually update kiya ja sakta hai.

4. Proof of Concept (PoC)
Target Endpoint: PATCH /api/users/8240627c-e0bf-486d-9409-c2300664e9e1

Attacker Role: Normal Customer

Victim Role: Restaurant Owner (Vikram Rathore)

Payload Sent:

JSON

        {
          "name": "Vikram Rathore - HACKED",
          "role": "admin",
          "isVerified": true,
          "_fraudFlag": false
        }
Response: {"success":true,"message":"Profile updated", ...}

<img width="1511" height="877" alt="Screenshot From 2026-04-27 17-50-38" src="https://github.com/user-attachments/assets/86387f2c-cfdb-4c91-9d48-29d98893a7ba" />

<img width="1509" height="850" alt="Screenshot From 2026-04-27 17-50-59" src="https://github.com/user-attachments/assets/0c7241c5-d295-486f-9571-f73c51226261" />

