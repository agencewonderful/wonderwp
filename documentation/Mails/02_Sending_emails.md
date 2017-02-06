# Sending emails

Minimum required to send an email:

- Get mailer
- Set sender
- Set reply to
- Set receiver(s)
- Set subject
- Set body
- Send mail

Example

```
$mailer = $container->offsetGet('wwp.emails.mailer'); //Get Mailer
$mail->setFrom($fromMail, $fromName); //Set Sender
$mail->setReplyTo($replyToMail,$replyToName); //Set Reply to
$mail->addTo($mailToMail, $mail); //Set receiver(s)
$mail->setSubject($subject); //Set subject
$mail->setBody($body); //Set body
$result = $mail->send(); //Send mail
```
