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
