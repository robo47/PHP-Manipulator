<?php

namespace PHP\Manipulator\Config;

use DOMAttr;
use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Iterator;
use LibXMLError;
use PHP\Manipulator\Config;
use PHP\Manipulator\Exception\ConfigException;
use Symfony\Component\Finder\Finder;

class XmlConfig extends Config
{
    /**
     * Init the config
     *
     * @param string $data
     */
    protected function initConfig($data)
    {
        $document = new DOMDocument();
        $old      = libxml_use_internal_errors(true);
        $loaded   = @$document->loadXML($data);
        if (!$loaded) {
            $message = sprintf('Unable to parse data: %s', $this->errorMessage(libxml_get_last_error()));
            throw new ConfigException($message, ConfigException::XML_PARSE_ERROR);
        }
        libxml_use_internal_errors($old);
        $this->parseOptions($document);
        $this->parseClassLoaders($document);
        $this->parseActions($document);
        $this->parseActionsets($document);
        $this->parseFiles($document);
        $this->parseDirectories($document);
        $this->parseIterators($document);
    }

    /**
     * @param LibXMLError $error
     *
     * @return string
     */
    private function errorMessage(LibXMLError $error = null)
    {
        $message = '';
        if ($error instanceof LibXMLError) {
            $message .= 'Level: '.$error->level.PHP_EOL;
            $message .= 'Code: '.$error->code.PHP_EOL;
            $message .= 'Column: '.$error->column.PHP_EOL;
            $message .= 'Message: '.$error->message.PHP_EOL;
            $message .= 'File: '.$error->file.PHP_EOL;
            $message .= 'Line: '.$error->line.PHP_EOL;
        }

        return $message;
    }

    /**
     * @param DOMDocument $document
     */
    private function parseOptions(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list  = $xpath->query('//config/options');
        foreach ($list as $node) {
            /* @var $node DOMNode */
            foreach ($node->childNodes as $option) {
                /* @var $option DOMNode */
                if ($option->nodeType === XML_ELEMENT_NODE) {
                    $this->addOption($option->nodeName, $option->nodeValue);
                }
            }
        }
    }

    /**
     * @param DOMNode $options
     *
     * @return array
     */
    private function parseActionOptions(DOMNode $options)
    {
        $actionOptions = [];
        foreach ($options->childNodes as $option) {
            if (strtolower($option->nodeName) === 'option') {
                $name  = $option->attributes->getNamedItem('name');
                $value = $option->attributes->getNamedItem('value');
                if (null !== $name && null !== $value) {
                    $cast = $option->attributes->getNamedItem('cast');
                    if (null !== $cast) {
                        $actionOptions[$name->value] = $this->castValue($cast->value, $value->value);
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
     *
     * @throws ConfigException
     *
     * @return mixed
     */
    private function castValue($type, $value)
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
                $message = sprintf('Unknown cast-type: %s', $type);
                throw new ConfigException($message, ConfigException::UNKNOWN_CAST_OPTION);
        }

        return $value;
    }

    /**
     * @param DOMDocument $document
     */
    private function parseActions(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list  = $xpath->query('//config/actions/action');
        foreach ($list as $option) {
            /* @var $option DOMNode */
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $options = $this->parseActionOptions($option);
                $prefix  = $option->attributes->getNamedItem('prefix');
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
     * @param DOMDocument $document
     */
    private function parseActionsets(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list  = $xpath->query('//config/actions/actionset');
        foreach ($list as $option) {
            /* @var $option DOMNode */
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
     * @param DOMDocument $document
     */
    private function parseFiles(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list  = $xpath->query('//config/files/file');
        foreach ($list as $option) {
            /* @var $option DOMNode */
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $this->addFile($option->nodeValue);
            }
        }
    }

    /**
     * @param DOMDocument $document
     */
    private function parseIterators(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list  = $xpath->query('//config/files/iterator');
        foreach ($list as $option) {
            /* @var $option DOMNode */
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $iterator = $this->parseIterator($option);
                if ($iterator instanceof Iterator) {
                    $this->addIterator($iterator);
                }
            }
        }
    }

    /**
     * @param DOMDocument $document
     */
    private function parseDirectories(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list  = $xpath->query('//config/files/directory');
        foreach ($list as $option) {
            /* @var $option DOMNode */
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $this->addDirectory($option->nodeValue);
            }
        }
    }

    /**
     * @param DOMNode $node
     *
     * @return Iterator|null
     */
    private function parseIterator(DOMNode $node)
    {
        $finder = new Finder();
        $finder->files();

        foreach ($node->childNodes as $option) {
            if ($option->nodeType === XML_ELEMENT_NODE) {
                $nodeName = strtolower($option->nodeName);
                $value    = $option->nodeValue;
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
     * @param DOMDocument $document
     */
    private function parseClassLoaders(DOMDocument $document)
    {
        $xpath = new DOMXpath($document);
        $list  = $xpath->query('//config/classloaders/classloader');
        /* @var $list DOMNodeList */
        foreach ($list as $classLoader) {
            /* @var $classLoader DOMNode */
            $attributes = $this->getAttributesAsArray($classLoader);
            if (isset($attributes['namespace'], $attributes['path'])) {
                $this->addClassLoader($attributes['namespace'], $attributes['path']);
            }
        }
    }

    /**
     * Parse array from attributes (key = name, value = value)
     *
     * @param DOMNode $node
     *
     * @return array
     */
    private function getAttributesAsArray(DOMNode $node)
    {
        $attributes = [];
        foreach ($node->attributes as $attribute) {
            $attributes[$attribute->name] = $attribute->value;
        }

        return $attributes;
    }
}
