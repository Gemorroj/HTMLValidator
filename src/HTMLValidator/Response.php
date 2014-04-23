<?php
/**
 * File contains a simple class structure for holding a response from the
 * W3C HTMLValidator software.
 *
 * PHP versions 5
 *
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 */
namespace HTMLValidator\HTMLValidator;

/**
 * Simple class for a W3C HTML Validator Response.
 *
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 */
class Response
{
    /**
     * the address of the document validated
     * 
     * Will (likely?) be upload://Form Submission  
     * if an uploaded document or fragment was validated. In EARL terms, this is
     * the TestSubject.
     * @var string
     */
    protected $uri;
    
    /**
     * Location of the service which provided the validation result. In EARL terms,
     * this is the Assertor.
     * @var string
     */
    protected $checkedby;
    
    /**
     * Detected (or forced) Document Type for the validated document
     * @var string
     */
    protected $doctype;
    
    /**
     * Detected (or forced) Character Encoding for the validated document
     * @var string
     */
    protected $charset;
    
    /**
     * Whether or not the document validated passed or not formal validation 
     * (true|false boolean)
     * @var bool
     */
    protected $validity;
    
    /**
     * Array of Error objects (if applicable)
     * @var Error[]
     */
    protected $errors = array();
    
    /**
     * Array of Warning objects (if applicable)
     * @var Warning[]
     */
    protected $warnings = array();

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     *
     * @return Response
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * @return string
     */
    public function getCheckedby()
    {
        return $this->checkedby;
    }

    /**
     * @param string $checkedby
     *
     * @return Response
     */
    public function setCheckedby($checkedby)
    {
        $this->checkedby = $checkedby;

        return $this;
    }

    /**
     * @return string
     */
    public function getDoctype()
    {
        return $this->doctype;
    }

    /**
     * @param string $doctype
     *
     * @return Response
     */
    public function setDoctype($doctype)
    {
        $this->doctype = $doctype;

        return $this;
    }

    /**
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param Error[] $errors
     *
     * @return Response
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     *
     * @return Response
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isValidity()
    {
        return $this->validity;
    }

    /**
     * @param boolean $validity
     *
     * @return Response
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;

        return $this;
    }

    /**
     * @return Warning[]
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * @param Warning[] $warnings
     *
     * @return Response
     */
    public function setWarnings(array $warnings)
    {
        $this->warnings = $warnings;

        return $this;
    }

    /**
     * @param Error $error
     *
     * @return Response
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * @param Warning $warning
     *
     * @return Response
     */
    public function addWarning(Warning $warning)
    {
        $this->warnings[] = $warning;

        return $this;
    }
}
