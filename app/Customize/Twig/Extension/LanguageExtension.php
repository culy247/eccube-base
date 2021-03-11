<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Customize\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LanguageExtension extends AbstractExtension
{
    /** @var ContainerInterface */
    protected $container;

    /** @param ContainerInterface $container */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return TwigFunction[] An array of functions
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('vue_messages', [$this, 'vueMessages']),
        ];
    }

    public function vueMessages($echo = false)
    {
        $translator = $this->container->get('translator');

        $english = $translator->getCatalogue('en')->all('vue');
        $japanese = $translator->getCatalogue('ja')->all('vue');

        $langs = [
            'en' => $english,
            'ja' => $japanese,
        ];

        return $echo ? json_encode($langs) : $langs;
    }
}
