<?php
/**
 * Módulo para exibir um formulário de contato.
 * @author Flávio Escobar
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$task = JRequest::getVar('task');

if( $task == 'mod_contact_form_sendmail' ) 
{
    $app = JFactory::getApplication();

    $mailfrom   = $app->getCfg('mailfrom');
    $fromname   = $app->getCfg('fromname');
    $sitename   = $app->getCfg('sitename');
    $nome       = JRequest::getVar('nome');
    $email      = JRequest::getVar('email');
    $prefix     = 'Este é um e-mail de consulta via ' . JUri::base() . ' enviado por ';
    $mensagem   = JRequest::getVar('mensagem');
    $mensagem   = $prefix."\n".$nome.' <'.$email.'>'."\r\n\r\n".stripslashes($mensagem);

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
     
    $query
        ->select('email_to')
        ->from($db->quoteName('#__contact_details'))
        ->where($db->quoteName('id') .' = ' . $params->get('id'));

    $db->setQuery($query);
    $row = $db->loadRow();

    $emailTo = $row[0];

    $mail = JFactory::getMailer();
    $mail->addRecipient( $emailTo );
    $mail->addReplyTo( array($email, $nome) );
    $mail->setSender( array($mailfrom, $fromname) );
    $mail->setSubject( $sitename.': Contato' );
    $mail->setBody( $mensagem );
     
    if ( $mail->Send() ) echo json_encode( array( 'sucesso' => 1, 'msg' => 'Mensagem enviada!' ) );
    else echo json_encode( array( 'sucesso' => 0, 'msg' => 'Erro. Tente Novamente.' ) );

    exit();
}

$doc =& JFactory::getDocument();
$doc->addStyleSheet('/media/mod_contact_form/css/style.css');

$doc->addScriptDeclaration('var urlAtual = "' . JURI::getInstance() . '";');
$doc->addScript("/media/system/js/validate.js");
$doc->addScript("/media/mod_contact_form/js/script.js");

?>

<form id="fale_conosco" class="form-validate FaleConosco">
    <p>Preencha os campos abaixo e entraremos em contato o mais breve possível</p>
    <input type="text" placeholder="Nome" name="nome" class="input_grid_4 required"/>
    <input type="text" placeholder="E-mail" name="email" class="input_grid_4 required"/>
    <textarea rows="4" placeholder="Mensagem" name="mensagem" class="input_grid_4 required"></textarea>
    <input type="submit" class="btn btn-primary pull-right" value="Enviar" />
    <div class="clearfix"></div> 

    <div class="msg_carregando msg_box">Enviando...</div>
    <div class="msg_sucesso msg_box"></div>
    <div class="msg_falha msg_box"></div>
</form>