<?php
/**
 * Class Renderer. Helps to render html pages
 *
 * @package Kernel\Helper
 * @author  Mikhail Lantukh <lantukhmikhail@gmail.com>
 */

namespace Kernel\Helper;

use Kernel\Exception\RendererException;

/**
 * Class Renderer
 *
 * @package Kernel\Helper
 */
class Renderer
{
    /**
     * The assigned to layout data
     *
     * @var array
     */
    private $_data = array();

    /**
     * The full path to layout for rendering
     *
     * @var string
     */
    private $_layout = '';

    /**
     * Constructor
     *
     * @param string $layout Layout to be set
     */
    public function __construct($layout = null)
    {
        if (is_string($layout)) {
            $this->setLayout($layout);
        }
    }


    /**
     * Assigns the data to layout for rendering
     *
     * @param string|array $key   The name of assigned value or a list of values
     * @param mixed        $value The assigned value
     *
     * @return Renderer
     */
    public function assign($key, $value = null)
    {
        if (is_array($key)) {
            $this->_data = array_merge($this->_data, $key);
        } else {
            $this->_data[$key] = $value;
        }

        return $this;
    }

    /**
     * Sets the full path to the layout to be rendered
     *
     * @param string $layout The full path to the layout
     *
     * @throws RendererException
     * @return Renderer
     */
    public function setLayout($layout)
    {
        if ($path = realpath($layout)) {
            $this->_layout = $path;
            return $this;
        }

        throw new RendererException(sprintf('Layout "%s" doesn\'t exist', $layout));
    }

    /**
     * Return the full path to the assigned layout
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * Returns assigned data
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Clean assigned data
     *
     * @return self
     */
    public function clean()
    {
        $this->_data = array();

        return $this;
    }

    /**
     * Renders the layout
     *
     * @throws RendererException If layout has not been set
     * @return string
     */
    public function render()
    {
        if (empty($this->_layout)) {
            throw new RendererException('Layout doesn\'t set');
        }
        ob_start();
        extract($this->getData());
        include $this->_layout;
        return ob_get_clean();
    }
}