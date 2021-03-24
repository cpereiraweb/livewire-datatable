<?php


namespace LuanFreitasDev\LivewireDataTables;

class Button
{

    private array $button = [];

    /**
     * Button constructor.
     * @param string $action
     */
    public function __construct(string $action)
    {
        $this->button['action'] = $action;
    }

    /**
     * @param string|null $action
     * @return Button
     */
    public static function add(string $action = null): Button
    {
        return new static($action);
    }

    /**
     * Button text in view
     * @param string $caption
     * @return $this
     */
    public function caption(string $caption): Button {
        $this->button['caption'] = $caption;
        return $this;
    }

    /**
     * @param string $route
     * @param array $param
     * @return $this
     */
    public function route(string $route, array $param): Button {
        $this->button['route'] = $route;
        $this->button['param'] = $param;
        return $this;
    }

    /**
     * Class string in view: class="bla bla bla"
     * @param string $class
     * @return $this
     */
    public function class(string $class): Button {
        $this->button['class'] = $class;
        return $this;
    }

    /**
     * @param string $class class of i tag for fontawesome or other
     * @param string $text text of caption
     * @param bool $showCaption when false it will not display the caption
     * @return $this
     */
    public function i(string $class, string $text, bool $showCaption=false): Button {
        $this->button['i'] = [
            'class' => $class,
            'text' => $text,
            'caption' => $showCaption
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function make(): array {
        return $this->button;
    }
}
