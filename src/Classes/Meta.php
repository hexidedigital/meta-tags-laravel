<?php

namespace HexideDigital\MetaTagsLaravel\Classes;

/**
 * Class Meta
 * @package HexideDigital\MetaTagsLaravel\Classes
 *
 * @method $this description(?string $str)
 * @method $this keywords(?string $str)
 * @method $this base(?string $str)
 * @method $this canonical(?string $str)
 * @method $this custom(?string $str)
 * @method $this social(?string $str)
 */
class Meta
{

    /**
     * @var string[]|array
     */
    private const tags = [
        "title"        => "<title>%s</title>\n",
        "description"  => "<meta name=\"description\" content=\"%s\"/>\n",
        "keywords"     => "<meta name=\"keywords\" content=\"%s\"/>\n",
        "base"         => "<base href=\"%s\"/>\n",
        "canonical"    => "<link rel=\"canonical\" href=\"%s\"/>\n",
        "custom"       => "%s\n",
        "social"       => "<meta property=\"%s\" content=\"%s\"/>\n",
    ];

    /**
     * @var string
     */
    private const titleDelimiter = " - ";

    /**
     * @var array
     */
    private array $data = [
        "title"         => [],
        "site_name"     => "",
        "description"   => "",
        "keywords"      => "",
        "base"          => "",
        "canonical"     => "",
        "url"           => "",
        "locale"        => "",
        "image"         => "",
        "custom"        => [],

        "social" => [
            // property         => content
            "og:title"              => "",
            "og:site_name"          => "",
            "og:description"        => "",
            "og:url"                => "",
            "og:image"              => "",
            "og:type"               => "website",
            "og:locale"             => "",

            "vk:image"              => "",
            "vk:title"              => "",
            "vk:description"        => "",

            "twitter:card"          => "summary",
            "twitter:image"         => "",
            "twitter:url"           => "",
            "twitter:title"         => "",
            "twitter:description"   => "",
        ]
    ];


    /**
     * @param string|null $str
     * @return $this
     */
    public function title(?string $str): self
    {
        if (!empty($str)) {
            array_unshift($this->data['title'], $str);
        }
        return $this;
    }

    /**
     * clear title
     * @return $this
     */
    public function clearTitle(): self
    {
        $this->data['title'] = [];

        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $output = sprintf(self::tags['title'], implode(self::titleDelimiter, $this->data['title']));

        if (!empty($this->data['description'])) {
            $output .= sprintf(self::tags['description'], $this->data['description']);
        }

        if (!empty($this->data['keywords'])) {
            $output .= sprintf(self::tags['keywords'], $this->data['keywords']);
        }

        if (!empty($this->data['canonical'])) {
            $output .= sprintf(self::tags['canonical'], $this->data['canonical']);
        }


        foreach ($this->data['custom'] as $custom) {
            $output .= sprintf(self::tags['custom'], $custom);
        }


        foreach ($this->data['social'] as $property => $value) {
            if (!empty($value)) {
                $output .= sprintf(self::tags['social'], $property, $value);
            } else {
                $value = self::_getSocialMeta($property);

                if (!empty($value)) {
                    $output .= sprintf(self::tags['social'], $property, $value);
                }
            }

        }

        return $output;
    }


    /**
     * @param string $property
     *
     * @return mixed
     */
    public function _get(string $property)
    {
        if (array_key_exists($property, $this->data)) {
            return $this->data[$property];
        }

        if (strtolower($property = substr($property, 0, 6) == 'social')) {
            /* not working with `twitter` social meta */
            $_property = strtolower(substr($property, 6, 2)) . ':' . strtolower(substr($property, 8));

            if (array_key_exists($_property, $this->data['social'])) {
                return $this->data['social'][$_property];
            }
        }

        /* not working with `twitter` social meta */
        $_property = strtolower(substr($property, 0, 2)) . ':' . strtolower(substr($property, 2));

        if (array_key_exists($_property, $this->data['social'])) {
            return $this->data['social'][$_property];
        }

        return "";
    }

    /**
     * @param string $property
     * @param mixed $arguments
     * @return Meta
     */
    public function _set(string $property, $arguments): Meta
    {
        if (array_key_exists($property, $this->data)) {
            $this->data[$property] = $arguments;
        }

        if (strtolower($property = substr($property, 0, 6) == 'social')) {
            /* not working with `twitter` social meta */
            $_property = strtolower(substr($property, 6, 2)) . ':' . strtolower(substr($property, 8));

            if (array_key_exists($_property, $this->data['social'])) {
                $this->data['social'][$property] = $arguments;
            }
        }

        /* not working with `twitter` social meta */
        $_property = strtolower(substr($property, 0, 2)) . ':' . strtolower(substr($property, 2));

        if (array_key_exists($_property, $this->data['social'])) {
            $this->data['social'][$_property] = $arguments;
        }

        $this->data[$property] = $arguments;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed $arguments
     *
     * @return $this|string|bool|mixed
     */
    public function __call(string $name, $arguments)
    {
        $action = substr($name, 0, 3);

        switch ($action) {
            case 'get':
                if (is_callable([$this, 'get' . $name])) {
                    return call_user_func([$this, 'get' . $name]);
                }

                $property = strtolower(substr($name, 3));

                return Meta::_get($property);

            case 'set':
                if (is_callable([$this, 'set' . $name])) {
                    return call_user_func([$this, 'set' . $name], $arguments);
                }

                $property = strtolower(substr($name, 3));

                return Meta::_set($property, $arguments[0]);

            default :
                $property = $name;

                if (!empty($arguments)) {
                    return self::_set($property, $arguments[0]);
                } else {
                    return self::_get($property);
                }
        }
    }

    /**
     * @param string $property
     *
     * @return string
     */
    private function _getSocialMeta(string $property): string
    {
        $property = explode(":", $property);
        $property = $property[1] ?? "";

        $content = $this->data[$property] ?? "";

        if (is_array($content)) {
            $content = implode(self::titleDelimiter, $content);
        }

        return $content;
    }
}
