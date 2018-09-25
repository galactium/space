<?php
/**
 * Galactium @ 2018
 * @author Grigoriy Ivanov
 */


namespace Galactium\Space\Mail;


use Phalcon\Config;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\View\Simple;

class Manager extends Injectable
{
    public const HTML = 'text/html';
    public const PLAIN = 'text/plain';
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Swift_Transport
     */
    protected $transport;

    /**
     * @var \Phalcon\Mvc\View\Simple
     */
    protected $view;

    /**
     * @var array
     */
    protected $failedRecipients = [];

    /**
     * @var \Phalcon\Config
     */
    protected $config = [];

    /**
     * Manager constructor.
     * @param \Swift_Mailer $mailer
     * @param \Swift_Transport $transport
     * @param \Phalcon\Config $config
     */
    public function __construct(\Swift_Mailer $mailer, \Swift_Transport $transport, Config $config)
    {
        $this->mailer = $mailer;
        $this->transport = $transport;
        $this->config = $config;

        $this->view = new Simple();

        $this->view->setDI($this->getDI());

        $this->view->setViewsDir($config->get('views_dir'));

        if ($this->di->has('volt')) {
            $this->view->registerEngines(['.volt' => $this->di->get('volt', [$this->view, $this->getDI()])]);
        }
    }


    /**
     * @return \Galactium\Space\Mail\Message
     */
    public function message(): Message
    {
        $message = new Message($this, new \Swift_Message());

        if ($this->config->get('from', false)) {
            $message->from(
                $this->config->path('from.email'),
                $this->config->path('from.name')
            );
        }

        if ($this->config->get('replyTo', false)) {
            $message->replyTo(
                $this->config->path('replyTo.email'),
                $this->config->path('replyTo.name')
            );
        }

        return $message;
    }

    /**
     * @return \Phalcon\Mvc\View\Simple
     */
    public function getView(): Simple
    {
        return $this->view;
    }

    /**
     * @param $message
     * @return $this
     */
    public function send($message)
    {
        $this->mailer->send($message, $this->failedRecipients);

        return $this;
    }

    /**
     * @return array
     */
    public function getFailedRecipients(): array
    {
        return $this->failedRecipients;
    }

    /**
     * @return \Swift_Mailer
     */
    public function getMailer(): \Swift_Mailer
    {
        return $this->mailer;
    }

    /**
     * @return \Swift_Transport
     */
    public function getTransport(): \Swift_Transport
    {
        return $this->transport;
    }


}