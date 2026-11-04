# Escaping Restricted Shells

*CPTS study notes*

## What is a restricted shell?

A restricted shell limits what a user can do at the command line — usually by blocking directory changes, restricting which commands can run, preventing environment variable edits, or confining execution to specific paths. Organizations use them to give lower-trust users (contractors, partners, limited-privilege accounts) safe, scoped access to a system without full shell capabilities.

## Common restricted shell types (Linux)

| Shell | Based on | Typical restrictions |
| --- | --- | --- |
| **rbash** | Bash | No `cd`, no editing env vars (PATH, SHELL, etc.), no running commands with `/` in the path, no output redirection |
| **rksh** | Korn shell (ksh) | No changing directories, no defining/editing shell functions, no modifying the shell environment |
| **rzsh** | Zsh | No running scripts, no defining aliases, no modifying the shell environment |

**Example enterprise use case:** an org tiers access by trust level — external partners get `rbash` (e.g., just email/file sharing), contractors get `rksh` (broader but still limited access to DB/web servers), and employees needing specific tooling get `rzsh` (most flexible, still constrained).

## Escape techniques

The general idea: restricted shells block *direct* invocation of certain commands or actions, but often fail to block indirect ways of reaching the same functionality. Below are the core categories.

### 1. Command injection

If the shell lets you pass arguments to an allowed command, you may be able to smuggle a second command in as part of that argument.

```bash
# Only `ls` is allowed, with flags like -l or -a
ls -l `pwd`
```

Here `pwd` runs inside backticks as part of the argument to `ls`, executing a command that isn't itself permitted by the shell's whitelist.

### 2. Command substitution

Similar concept — abuse the shell's substitution syntax (backticks `` ` ` `` or `$()`) to execute an arbitrary command wherever substitution is evaluated, even if that command wouldn't be allowed on its own.

### 3. Command chaining

If the shell permits metacharacters like `;`, `|`, `&&`, or `||` between commands, you can chain an allowed command with a restricted one:

```bash
allowed_command ; restricted_command
```

### 4. Environment variable manipulation

If the shell relies on environment variables to decide things like execution paths (e.g., `PATH`), modifying those variables can redirect execution outside the intended restrictions.

### 5. Shell functions

If function definition isn't blocked, define a function that wraps or calls a restricted command — the function itself may not trigger the same checks as calling the command directly.

## Key exam/exploitation takeaway

Restricted shells rely on **blocklisting specific invocation paths**, not on sandboxing execution itself. Almost every escape technique above exploits the same underlying gap: the shell checks *what you typed directly*, not *what ends up executing* after substitution, injection, chaining, or indirection. When testing a restricted shell, systematically try each category — argument injection, substitution, chaining, env var abuse, and function definition — since different restricted shells block different subsets of these.

## Quick reference checklist for engagements

- [ ] Can I pass arbitrary arguments to an allowed command? → try injection via backticks/`$()`
- [ ] Are `;`, `|`, `&&` filtered? → test chaining
- [ ] Can I read/write environment variables (`PATH`, `SHELL`, `ENV`)? → test redirection via env vars
- [ ] Can I define shell functions/aliases? → wrap restricted commands
- [ ] Can I invoke an editor, pager, or scripting interpreter from within an allowed command? (e.g., `vi`, `less`, `awk`, `perl` — classic GTFOBins-style breakouts)
- [ ] Does the shell restrict by full path only? → test relative paths or `cd`-based tricks if `cd` is allowed
