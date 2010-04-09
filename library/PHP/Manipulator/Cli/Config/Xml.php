<?php

namespace PHP\Manipulator\Cli\Config;

use PHP\Manipulator\Cli\Config;
use PHP\Manipulator\Cli\Config\Xml;

class Xml extends Config
{

    protected function _initConfig($data)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($data);
        $this->_parseOptions($dom);
        $this->_parseRules($dom);
        $this->_parseFiles($dom);
    }

    /**
     * Parses Options out of the DOMDocument
     *
     * @param DOMDocument $dom
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

    protected function _parseRuleOptions(\DOMNode $options)
    {
        $ruleOptions = array();
        foreach($options->childNodes as $option) {
            if (strtolower($option->nodeName) == 'option') {
                $name = $option->attributes->getNamedItem('name');
                $value = $option->attributes->getNamedItem('value');
                if (null !== $name && null !== $value) {
                    $ruleOptions[$name->value] = $value->value;
                }
            }
        }
        return $ruleOptions;
    }

    /**
     * Parses Rules out of the DOMDocument
     *
     * @param DOMDocument $dom
     */
    protected function _parseRules(\DOMDocument $dom)
    {
        $xpath = new \DOMXpath($dom);
        $list = $xpath->query('//config/rules');
        foreach($list as $node) {
            /* @var $node DOMNode*/
            foreach($node->childNodes as $option) {
                /* @var $option DOMNode*/
                if ($option->nodeType === XML_ELEMENT_NODE) {
                    $nodeName = strtolower($option->nodeName);
                    switch($nodeName) {
                        case 'ruleset':
                            $prefix = $option->attributes->getNamedItem('prefix');
                            if ($prefix instanceof \DOMAttr) {
                                $prefix = $prefix->value;
                            }
                            $name = $option->attributes->getNamedItem('name');
                            if ($name instanceof \DOMAttr) {
                                $this->addRuleset($name->value, $prefix);
                            }
                            break;
                        case 'rule':
                            $options = $this->_parseRuleOptions($option);
                            $prefix = $option->attributes->getNamedItem('prefix');
                            if ($prefix instanceof \DOMAttr) {
                                $prefix = $prefix->value;
                            }
                            $name = $option->attributes->getNamedItem('name');
                            if ($name instanceof \DOMAttr) {
                                $this->addRule($name->value, $prefix, $options);
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }
    }

    /**
     * Parses Files out of the DOMDocument
     *
     * @param DOMDocument $dom
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
                        default:
                        // ignore
                            break;
                    }
                }
            }
        }
    }


}