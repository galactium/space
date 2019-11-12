<?php
/**
 * Galactium @ 2020
 * @author Grigoriy Ivanov
 */


namespace Galactium\Space\Mail;

use Swift_Attachment;
use Swift_Image;
use Swift_Message;

/**
 * Class Message
 * @package Galactium\Space\Mail
 */
class Message
{
    /**
     * @var Manager
     */
    protected $manager;
    /**
     * @var Swift_Message
     */
    protected $swiftMessage;

    /**
     * Message constructor.
     * @param Manager $manager
     * @param Swift_Message $swiftMessage
     */
    public function __construct(Manager $manager, Swift_Message $swiftMessage)
    {
        $this->manager = $manager;
        $this->swiftMessage = $swiftMessage;
    }

    /**
     * @param string $viewPath
     * @param array $params
     * @param callable|null $callback
     * @return $this
     */
    public function view(string $viewPath, $params = [], callable $callback = null)
    {
        $view = $this->manager->getView();

        if (is_callable($callback)) {
            $callback($view);
        }

        $view->setVar('_message', $this);

        $this->swiftMessage->setContentType(Manager::HTML);
        $this->body($view->render($viewPath, $params));

        return $this;

    }

    /**
     * @param string $message
     * @return $this
     */
    public function body(string $message)
    {
        $this->swiftMessage->setBody($message);
        return $this;
    }

    /**
     * @param string $address
     * @param string $name
     * @return $this
     */
    public function from(string $address, string $name = null)
    {
        $this->swiftMessage->setFrom($address, $name);
        return $this;
    }

    /**
     * @param $address
     * @param string $name
     * @return $this
     */
    public function sender(string $address, string $name = null)
    {
        $this->swiftMessage->setSender($address, $name);
        return $this;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function returnPath(string $address)
    {
        $this->swiftMessage->setReturnPath($address);
        return $this;
    }

    /**
     * @param string|array $address
     * @param string|null $name
     * @param bool $override
     * @return $this|Message
     */
    public function to($address, string $name = null, $override = false)
    {
        if ($override) {
            $this->swiftMessage->setTo($address, $name);
            return $this;
        }
        return $this->addAddresses($address, $name, 'To');
    }

    /**
     * @param string|array $address
     * @param string $name
     * @param string $type
     * @return $this
     */
    protected function addAddresses($address, ?string $name, string $type)
    {
        if (is_array($address)) {
            $this->swiftMessage->{"set{$type}"}($address, $name);
        } else {
            $this->swiftMessage->{"add{$type}"}($address, $name);
        }
        return $this;
    }

    /**
     * @param string|array $address
     * @param string|null $name
     * @param bool $override
     * @return $this|Message
     */
    public function cc($address, string $name = null, $override = false)
    {
        if ($override) {
            $this->swiftMessage->setCc($address, $name);
            return $this;
        }
        return $this->addAddresses($address, $name, 'Cc');
    }

    /**
     * @param string|array $address
     * @param string|null $name
     * @param bool $override
     * @return $this|Message
     */
    public function bcc($address, string $name = null, $override = false)
    {
        if ($override) {
            $this->swiftMessage->setBcc($address, $name);
            return $this;
        }
        return $this->addAddresses($address, $name, 'Bcc');
    }

    /**
     * @param $address
     * @param string|null $name
     * @return Message
     */
    public function replyTo($address, string $name = null)
    {
        return $this->addAddresses($address, $name, 'ReplyTo');
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function subject(string $subject)
    {
        $this->swiftMessage->setSubject($subject);
        return $this;
    }

    /**
     * @param int $level
     * @return $this
     */
    public function priority(int $level)
    {
        $this->swiftMessage->setPriority($level);
        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function callback(callable $callback)
    {
        $callback($this->swiftMessage);

        return $this;
    }

    /**
     * @param string $file
     * @param string|null $fileName
     * @param string|null $mime
     * @return $this
     */
    public function attach(string $file, string $fileName = null, string $mime = null)
    {
        $attachment = Swift_Attachment::fromPath($file);

        if ($fileName) {
            $attachment->setFilename($fileName);
        }

        if ($mime) {
            $attachment->setContentType($mime);
        }

        $this->swiftMessage->attach($attachment);
        return $this;
    }

    /**
     * @param string $file
     * @return string
     */
    public function embed(string $file)
    {
        return $this->swiftMessage->embed(Swift_Image::fromPath($file));
    }

    /**
     * @return Manager
     */
    public function send()
    {
        return $this->manager->send($this->swiftMessage);
    }

    /**
     * @return Swift_Message
     */
    public function getSwiftMessage(): Swift_Message
    {
        return $this->swiftMessage;
    }


}