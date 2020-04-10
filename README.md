## Emeow

![Emeow](https://i.imgur.com/ecTM5DI.png)

```
Description:
  Emeow is a mailman who knows how to handle mail and network

Usage:
  emeow [options] [--] <recipient>

Arguments:
  recipient                    Recipient address

Options:
      --subject=SUBJECT        Email Subject [default: "You got Emeow!"]
      --mailer-dsn=MAILER-DSN  E.g. gmail+stream://USERNAME:PASSWORD@default?ip=1.2.3.4
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