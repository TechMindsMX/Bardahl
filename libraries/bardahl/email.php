<?php

jimport('joomla.factory');


class send_email{

    public $responses;
    protected $recipient;

    public function envia($correo)
    {

        $mailer = JFactory :: getMailer ();
        $Config = JFactory :: getConfig ();

        $remitente = array (
            $Config['mailfrom'],
            $Config['fromname']);
        $mailer->setSender($remitente);

        $mailer->addRecipient($correo);
        $body   = 'Envio pdf correo';
        $title  = 'Recomendacion de producto';
        $mailer->addAttachment('productosPdf/archivo.pdf');
        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';
        $mailer->setSubject($title);
        $mailer->setBody($body);
        $send = $mailer->Send();
        return $send;
    }


}