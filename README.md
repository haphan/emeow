## Emeow

```
Description:
  Emeow is a mailman who knows how to handle mail and network

Usage:
  emeow [options] [--] <recipient>

Arguments:
  recipient                    Recipient address

Options:
      --mailer-dsn=MAILER-DSN  E.g. gmail+stream://USERNAME:APP-PASSWORD@default
      --ifname=IFNAME          Bind network context to a network interface e.g. eth1
      --ifaddr=IFADDR          Bind network context to a network address e.g. 172.16.1.2
      --var=VAR                Variables to be used in email template in format KEY=VALUE (multiple values allowed)
      --tpl=TPL                Email template file [default: "email.twig"]
      --html                   Send email in html mode. Default is txt mode
```


## Examples

Send an email to `recipient@example.com` using template defined default location `email.twig`

```bash
emeow --mailer-dns=gmail+stream://foobar:your-app-password-here@default --  recipient@example.com
```

Send and email using custom source IP address

```bash
emeow --mailer-dns=gmail+stream://foobar:your-app-password-here@default?ip=192.168.0.123 \
    -- \
    recipient@example.com
```

Send and email with custom variables and template file
```bash
emeow --mailer-dns=gmail+stream://foobar:your-app-password-here@default \
    --var 'name=John Doe' \
    --var host=`hostname` \
    --tpl custom.twig \
    -- \
    recipient@example.com
```