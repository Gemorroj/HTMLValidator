<?php
namespace HTMLValidator\HTMLValidator;

abstract class Message
{
    /**
     * line corresponding to the message
     * 
     * Within the source code of the validated document, refers to the line which
     * caused this message.
     * @var int
     */
    protected $line;
    
    /**
     * column corresponding to the message
     * 
     * Within the source code of the validated document, refers to the column within
     * the line for the message.
     * @var int
     */
    protected $col;
    
    /**
     * The actual message
     * @var string
     */
    protected $message;
    
    /**
     * Unique ID for this message
     * 
     * not implemented yet. should be the number of the error, as addressed
     * internally by the validator
     * @var int
     */
    protected $messageid;
    
    /**
     * Explanation for this message.
     * 
     * HTML snippet which describes the message, usually with information on
     * how to correct the problem.
     * @var string
     */
    protected $explanation;
    
    /**
     * Source which caused the message.
     * 
     * the snippet of HTML code which invoked the message to give the 
     * context of the e
     * @var string
     */
    protected $source;

    /**
     * @return int
     */
    public function getCol()
    {
        return $this->col;
    }

    /**
     * @param int $col
     *
     * @return Message
     */
    public function setCol($col)
    {
        $this->col = $col;

        return $this;
    }

    /**
     * @return string
     */
    public function getExplanation()
    {
        return $this->explanation;
    }

    /**
     * @param string $explanation
     *
     * @return Message
     */
    public function setExplanation($explanation)
    {
        $this->explanation = $explanation;

        return $this;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param int $line
     *
     * @return Message
     */
    public function setLine($line)
    {
        $this->line = $line;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return int
     */
    public function getMessageid()
    {
        return $this->messageid;
    }

    /**
     * @param int $messageid
     *
     * @return Message
     */
    public function setMessageid($messageid)
    {
        $this->messageid = $messageid;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     *
     * @return Message
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Constructor for a response message
     *
     * @param \DOMElement $node A dom document node.
     */
    public function __construct(\DOMElement $node = null)
    {
        if ($node) {
            foreach (get_class_vars(__CLASS__) as $var => $val) {
                $element = $node->getElementsByTagName($var);
                if ($element->length) {
                    $this->{'set' . ucfirst($var)}($element->item(0)->nodeValue);
                }
            }
        }
    }
}
