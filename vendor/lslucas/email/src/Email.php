<?php namespace lslucas\Email;

class Email {

    public static function transport()
    {
        $smtp_host_ip = gethostbyname('smtp.gmail.com');
        // Create the SMTP configuration
        $transport = \Swift_SmtpTransport::newInstance($smtp_host_ip, 465, 'ssl');
        $transport->setUsername("noreply@techtravel.com.br");
        $transport->setPassword("mvdbt9TT");

        return $transport;
    }

    // Converte um caminho de view para html
    public static function send($data)
    {
        $transport = self::transport();

        if ( !isset($data['from']) )
            $data['from'] = \Config::get('mail.from');
        if ( !isset($data['reply']) )
            $data['reply'] = \Config::get('mail.reply');

        $html = \View::make($data['view'])->with('data', $data);

        $message = \Swift_Message::newInstance('-f %s')
            ->setSubject($data['subject'])
            ->setFrom(array($data['from']['email']=>$data['from']['name']))
            ->setTo(array($data['to']['email']=>$data['to']['name']))
            ->addPart($html, 'text/html')
            ;

        if ( isset($data['attach']) )
            $message->attach(\Swift_Attachment::fromPath($data['attach']));

        // Send the email
        $failedRecipients = [];
        $mailer = \Swift_Mailer::newInstance($transport);
        $mailer->send($message, $failedRecipients);

        // Show failed recipients
        return !count($failedRecipients) ? true : $failedRecipients;
    }

}
