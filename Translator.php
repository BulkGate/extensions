<?php
namespace BulkGate\Extensions;

/**
 * @author Lukáš Piják 2018 TOPefekt s.r.o.
 * @link https://www.bulkgate.com/
 */
class Translator extends SmartObject
{
    /** @var ISettings */
    private $settings;

    /** @var string|null|bool */
    private $iso = null;

    /** @var array */
    private $translates = array();

    public function __construct(ISettings $settings)
    {
        $this->settings = $settings;
    }

    public function init()
    {
        $this->iso = isset($_COOKIE['language_iso']) ? $_COOKIE['language_iso'] : 'en';
        setcookie('language_iso', $this->iso, 0, '/');

        if($this->iso)
        {
            $translates = (array) $this->settings->load('translates:'.$this->iso);

            if($translates && is_array($translates))
            {
                $this->translates = $translates;
            }
        }
    }

    public function translate($key, $default = null)
    {
        if($this->iso === null)
        {
            $this->init();
        }

        if(isset($this->translates[$key]))
        {
            return $this->translates[$key];
        }

        if($default === null)
        {
            return ucfirst(str_replace('_', ' ', $key));
        }

        return $default;
    }
}