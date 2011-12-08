<?php

namespace PHP\Manipulator\Config;

use PHP\Manipulator\Config,
 PHP\Manipulator\Config\Xml;
use Symfony\Component\Finder\Finder;
use DOMNode,
 DOMAttr,
 DOMDocument,
 DOMXpath,
 LibXMLError;
use Exception;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 */
class Xml extends Config
{

    /**
     * Init the config
     *
     * @param string $data
     */
    protected function _initConfig($data)
    {
        $document = new DOMDocument();
        $old = libxml_use_internal_errors(true);
        $loaded = @$document->loadXML($data);
        if (!$loaded) {
            $error = $this->_errorMessage(libxml_get_last_error());
            throw new Exception('Unable to parse data: ' . PHP_EOL . $error);
        }
        libxml_use_internal_errors($old);
        $this->_parseOptions($document);
        $this->_parseClassLoaders($document);
        $this->_parseActions($document);
        $this->_parseActionsets($document);
        $this->_parseFiles($document);
        $this->_parseDirectories($document);
        $this->_parseIterators($document);
    }

    /**
     * Get error message
     *
     * @param \LibXMLError $error
     * @return string
     */
    protected function _errorMessage(LibXMLError $error = null)
    {
        $message = '';
        if ($error instanceof LibXMLError) {
            /* @var $error LibXMLError */
            $message .= 'Level: ' . $error->level . PHP_EOL;
            $message .= 'Code: ' . $error->code . PHP_EOL;
            $message .= 'Column: ' . $error->column . PHP_EOL;
            $message .= 'Message: ' . $error->message . PHP_EOL;
            $message .= 'File: ' . $error->file . PHP_EOL;
            $message .= 'Line: ' . $error->line . PHP_EOL;
        }
        return $message;
    }

    /**
     * Parses Options out of the DOMDocument
     *
     * @param \DOMDocument $dom
     */
    protected function _parseOptions(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list = $xpath->query('//config/options');
        foreach ($list as $node) {
            /* @var $node DOMNode */
            foreach ($node->childNodes as $option) {
                /* @var $option DOMNode */
                if ($option->nodeType === XML_ELEMENT_NODE) {
                    $this->_options[$option->nodeName] = $option->nodeValue;
                }
            }
        }
    }

    /**
     * @param \DOMNode $options
     * @return array
     */
    protected function _parseActionOptions(DOMNode $options)
    {
        $actionOptions = array();
        foreach ($options->childNodes as $option) {
            if (strtolower($option->nodeName) === 'option') {
                $name = $option->attributes->getNamedItem('name');
                $value = $option->attributes->getNamedItem('value');
                if (null !== $name && null !== $value) {
                    $cast = $option->attributes->getNamedItem('cast');
                    if (null !== $cast) {
                        $actionOptions[$name->value] = $this->_castValue($cast->value, $value->value);
                    } else {
                        $actionOptions[$name->value] = $value->value;
                    }
                }
            }
        }
        return $actionOptions;
    }

    /**
     * @param string $type
     * @param string $value
     * @return mixed
     */
    protected function _castValue($type, $value)
    {
        $type = strtolower($type);
        switch ($type) {
            case 'boolean':
            case 'bool':
                $value = (bool) $value;
                break;
            case 'int':
            case 'integer':
                $value = (int) $value;
                break;
            case 'array':
                $value = (array) $value;
                break;
            case 'object':
                $value = (object) $value;
                break;
            case 'linebreaks':
                $value = str_replace('\n', "\n", $value);
                $value = str_replace('\r', "\r", $value);
                break;
            case 'float':
            case 'double':
            case 'real':
                $value = (float) $value;
                break;
            default:
                throw new Exception('unknown cast-type: ' . $type);
        }
        return $value;
    }

    /**
     * @param \DOMDocument $dom
     */
    protected function _parseActions(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list = $xpath->query('//config/actions/action');
        foreach ($list as $option) {
            /* @var $option \DOMNode */
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $options = $this->_parseActionOptions($option);
                $prefix = $option->attributes->getNamedItem('prefix');
                if ($prefix instanceof DOMAttr) {
                    $prefix = $prefix->value;
                }
                $name = $option->attributes->getNamedItem('name');
                if ($name instanceof DOMAttr) {
                    $this->addAction($name->value, $prefix, $options);
                }
            }
        }
    }

    /**
     * @param \DOMDocument $dom
     */
    protected function _parseActionsets(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list = $xpath->query('//config/actions/actionset');
        foreach ($list as $option) {
            /* @var $option \DOMNode */
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $prefix = $option->attributes->getNamedItem('prefix');
                if ($prefix instanceof DOMAttr) {
                    $prefix = $prefix->value;
                }
                // @todo Actionset-options
                $name = $option->attributes->getNamedItem('name');
                if ($name instanceof DOMAttr) {
                    $this->addActionset($name->value, $prefix);
                }
            }
        }
    }

    /**
     * @param \DOMDocument $dom
     */
    protected function _parseFiles(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list = $xpath->query('//config/files/file');
        foreach ($list as $option) {
            /* @var $option \DOMNode */
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $this->addFile($option->nodeValue);
            }
        }
    }

    /**
     * @param \DOMDocument $dom
     */
    protected function _parseIterators(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list = $xpath->query('//config/files/iterator');
        foreach ($list as $option) {
            /* @var $option \DOMNode */
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $iterator = $this->_parseIterator($option);
                if ($iterator instanceof \Iterator) {
                    $this->addIterator($iterator);
                }
            }
        }
    }

    /**
     * @param \DOMDocument $dom
     */
    protected function _parseDirectories(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list = $xpath->query('//config/files/directory');
        foreach ($list as $option) {
            /* @var $option \DOMNode */
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $this->addDirectory($option->nodeValue);
            }
        }
    }

    /**
     * @param \DOMNode $node
     * @return \Iterator|null
     */
    protected function _parseIterator(DOMNode $node)
    {
        $finder = new Finder();
        $finder->files();

        foreach ($node->childNodes as $option) {
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $nodeName = strtolower($option->nodeName);
                $value = $option->nodeValue;
                switch ($nodeName) {
                    case 'name':
                        $finder->name($value);
                        break;
                    case 'notname':
                        $finder->notName($value);
                        break;
                    case 'path':
                        $finder->in($value);
                        break;
                    case 'size':
                        $finder->size($value);
                        break;
                    case 'exclude':
                        $finder->exclude($value);
                        break;
                }
            }
        }
        return $finder->getIterator();
    }

    /**
     * @param \DOMDocument $dom
     */
    protected function _parseClassLoaders(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list = $xpath->query('//config/classloaders/classloader');
        /* @var $list DOMNodeList */
        foreach ($list as $classLoader) {
            /* @var $classLoader DOMNode */
            $attributes = $this->_getAttributesAsArray($classLoader);
            if (isset($attributes['namespace'], $attributes['path'])) {
                $this->addClassLoader($attributes['namespace'], $attributes['path']);
            }
        }
    }

    /**
     * Parse array from attributes (key = name, value = value)
     *
     * @param \DOMNode $node
     * @return array
     */
    protected function _getAttributesAsArray(DOMNode $node)
    {
        $attributes = array();
        foreach ($node->attributes as $attribute) {
            $attributes[$attribute->name] = $attribute->value;
        }
        return $attributes;
    }

}