<?php

namespace PHP\Manipulator\Config;

use PHP\Manipulator\Config;
use PHP\Manipulator\Config\Xml;
use Symfony\Components\Finder\Finder;

class Xml extends Config
{

    /**
     * Init the config
     *
     * @param string $data
     */
    protected function _initConfig($data)
    {
        $dom = new \DOMDocument();
        $old = \libxml_use_internal_errors(true);
        $loaded = @$dom->loadXML($data);
        if (!$loaded) {
            $error = $this->_errorMessage(\libxml_get_last_error());
            throw new \Exception('Unable to parse data: ' . PHP_EOL . $error);
        }
        \libxml_use_internal_errors($old);
        $this->_parseOptions($dom);
        $this->_parseClassLoaders($dom);
        $this->_parseActions($dom);
        $this->_parseFiles($dom);
    }

    /**
     * Get error message
     *
     * @param \LibXMLError $error
     * @return string
     */
    protected function _errorMessage(\LibXMLError $error = null)
    {
        $message = '';
        if ($error instanceof \LibXMLError) {
            /* @var $error libXMLError */
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
    protected function _parseOptions(\DOMDocument $dom)
    {
        $xpath = new \DOMXpath($dom);
        $list = $xpath->query('//config/options');
        foreach ($list as $node) {
            /* @var $node DOMNode*/
            foreach ($node->childNodes as $option) {
                /* @var $option DOMNode*/
                if ($option->nodeType === XML_ELEMENT_NODE) {
                    $this->_options[$option->nodeName] = $option->nodeValue;
                }
            }
        }
    }

    /**
     * Parse Actions-Options out of the DOMNode
     *
     * @param \DOMNode $options
     * @return array
     */
    protected function _parseActionOptions(\DOMNode $options)
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
     *
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
            case 'string':
                $value = (string) $value;
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
                throw new \Exception('unknown cast-type: ' . $type);
                break;
        }
        return $value;
    }

    /**
     * Parses Actions out of the DOMDocument
     *
     * @param \DOMDocument $dom
     */
    protected function _parseActions(\DOMDocument $dom)
    {
        $xpath = new \DOMXpath($dom);
        $list = $xpath->query('//config/actions');
        foreach ($list as $node) {
            /* @var $node DOMNode*/
            foreach ($node->childNodes as $option) {
                /* @var $option DOMNode*/
                if ($option->nodeType === XML_ELEMENT_NODE) {
                    $nodeName = strtolower($option->nodeName);
                    switch ($nodeName) {
                        case 'actionset':
                            $prefix = $option->attributes->getNamedItem('prefix');
                            if ($prefix instanceof \DOMAttr) {
                                $prefix = $prefix->value;
                            }
                            $name = $option->attributes->getNamedItem('name');
                            if ($name instanceof \DOMAttr) {
                                $this->addActionset($name->value, $prefix);
                            }
                            break;
                        case 'action':
                            $options = $this->_parseActionOptions($option);
                            $prefix = $option->attributes->getNamedItem('prefix');
                            if ($prefix instanceof \DOMAttr) {
                                $prefix = $prefix->value;
                            }
                            $name = $option->attributes->getNamedItem('name');
                            if ($name instanceof \DOMAttr) {
                                $this->addAction($name->value, $prefix, $options);
                            }
                            break;
                    }
                }
            }
        }
    }

    /**
     * Parses Files out of the DOMDocument
     *
     * @param \DOMDocument $dom
     */
    protected function _parseFiles(\DOMDocument $dom)
    {
        $xpath = new \DOMXpath($dom);
        $list = $xpath->query('//config/files');
        foreach ($list as $node) {
            /* @var $node DOMNode*/
            foreach ($node->childNodes as $option) {
                /* @var $options DOMNode*/
                if ($option->nodeType === XML_ELEMENT_NODE) {
                    $nodeName = strtolower($option->nodeName);
                    switch ($nodeName) {
                        case 'file':
                            $this->addFile($option->nodeValue);
                            break;
                        case 'directory':
                            $this->addDirectory($option->nodeValue);
                            break;
                        case 'iterator':
                            $iterator = $this->_parseIterator($option);
                            if ($iterator instanceof \Iterator) {
                                $this->addIterator($iterator);
                            }
                            break;
                    }
                }
            }
        }
    }

    /**
     * Parse iterator
     *
     * @param \DOMNode $node
     * @return \Iterator|null
     */
    protected function _parseIterator(\DOMNode $node)
    {
        $finder = new Finder();
        foreach ($node->childNodes as $option) {
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $nodeName = strtolower($option->nodeName);
                $value = $option->nodeValue;
                switch ($nodeName) {
                    case 'prefix':
                        $finder->name($value .'*');
                        break;
                    case 'suffix':
                        $finder->name('*' . $value);
                        break;
                    case 'path':
                        $finder->in($value);
                        break;
                }
            }
        }
        return $iterator = $finder->files()->getIterator();
    }

    /**
     * @param DOMDocument $dom
     */
    protected function _parseClassLoaders(\DOMDocument $dom)
    {
        $xpath = new \DOMXpath($dom);
        $list = $xpath->query('//config/classloaders/classloader');
        /* @var $list DOMNodeList */
        foreach ($list as $classLoader) {
            /* @var $classLoader DOMNode*/
            if (strtolower($classLoader->tagName) === 'classloader') {
                $attributes = $this->_getAttributesAsArray($classLoader);
                if (isset($attributes['namespace'], $attributes['path'])) {
                    $this->addClassLoader($attributes['namespace'], $attributes['path']);
                }
            }
        }
    }

    /**
     * Parse array from attributes (key = name, value = value)
     *
     * @param \DOMNode $node
     * @return array
     */
    protected function _getAttributesAsArray(\DOMNode $node)
    {
        $attributes = array();
        foreach ($node->attributes as $attribute) {
            $attributes[$attribute->name] = $attribute->value;
        }
        return $attributes;
    }
}